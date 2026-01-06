<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use App\Models\CategoryModel;
use App\Models\StockLogModel;

class SaleController extends BaseController
{
    protected $saleModel;
    protected $saleItemModel;
    protected $productModel;
    protected $clientModel;
    protected $categoryModel;
    protected $stockLogModel;
    protected $db;

    public function __construct()
    {
        $this->saleModel     = new SaleModel();
        $this->saleItemModel = new SaleItemModel();
        $this->productModel  = new ProductModel();
        $this->clientModel   = new ClientModel();
        $this->categoryModel = new CategoryModel();
        $this->stockLogModel = new StockLogModel();
        $this->db            = \Config\Database::connect();
    }

    public function index()
    {
        // Fetch sales with client names
        $data['sales'] = $this->saleModel
            ->select('sales.*, clients.name as client_name')
            ->join('clients', 'clients.id = sales.client_id', 'left')
            ->orderBy('sales.created_at', 'DESC')
            ->findAll();

        return view('sales/index', $data);
    }

    public function new()
    {
        $data['clients']  = $this->clientModel->findAll();
        $data['products'] = $this->productModel
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->orderBy('categories.name', 'ASC')
            ->orderBy('products.name', 'ASC')
            ->findAll();
        $data['categories'] = $this->categoryModel->findAll(); // Added
        return view('sales/new', $data);
    }

    public function store()
    {
        // Expecting JSON input for simpler POS logic
        $json = $this->request->getJSON();
        
        if (!$json) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        $clientId = $json->client_id ?? null;
        $items    = $json->items ?? [];
        $type     = $json->type ?? 'retail';
        $globalDiscount = $json->discount ?? 0; // Global Discount
        $paymentMethod = $json->payment_method ?? 'cash'; // Payment Method

        if (empty($items)) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'No items in cart']);
        }

        $this->db->transStart();

        try {
            // Calculate total
            $total = 0;
            foreach ($items as $item) {
                $itemDiscount = $item->discount ?? 0;
                $total += ($item->price * $item->quantity) - $itemDiscount;
            }
            
            // Apply Global Discount
            $total -= $globalDiscount;

            // Create Sale
            $saleId = $this->saleModel->insert([
                'client_id' => $clientId,
                'total'     => $total,
                'discount'  => $globalDiscount,
                'payment_method' => $paymentMethod,
                'type'      => $type,
            ]);

            if (!$saleId) {
                 $errors = $this->saleModel->errors();
                 throw new \Exception("Error creating sale: " . implode(", ", $errors));
            }

            foreach ($items as $item) {
                // Verify Stock
                $product = $this->productModel->find($item->product_id);
                if (!$product) {
                    throw new \Exception("Product not found: " . $item->product_id);
                }
                
                if ($product['stock_quantity'] < $item->quantity) {
                     throw new \Exception("Insufficient stock for product: " . $product['name']);
                }

                // Deduct Stock
                $this->productModel->update($item->product_id, [
                    'stock_quantity' => $product['stock_quantity'] - $item->quantity
                ]);

                // Create Sale Item
                $this->saleItemModel->insert([
                    'sale_id'    => $saleId,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->price,
                    'discount'   => $item->discount ?? 0,
                ]);

                // Log Stock Change
                $this->stockLogModel->insert([
                    'product_id'    => $item->product_id,
                    'user_id'       => session()->get('id') ?? 1, // Fallback to 1 (Admin/System) if session missing
                    'change_amount' => -$item->quantity,
                    'reason'        => 'Venta #' . $saleId
                ]);
            }

            // Handle Current Account
            if ($paymentMethod === 'current_account') {
                if ($clientId) {
                    // Determine movement type: Credit Sale = Debit (Increases Client Debt)
                    // We decided: Client Balance = Debt.
                    // Sale (Debt Increase) => Add to Balance.
                    // Wait, Controller Logic: 
                    // Client Payment (Credit) => Balance - Amount.
                    // Sale (Debit) => Balance + Amount.
                    
                    $client = $this->clientModel->find($clientId);
                    if ($client) {
                        $newBalance = $client['account_balance'] + $total;
                        $this->clientModel->update($clientId, ['account_balance' => $newBalance]);

                        // Add Movement
                        // We need AccountMovementModel here. Let's create an instance since we didn't inject it.
                        $movementModel = new \App\Models\AccountMovementModel();
                        $movementModel->insert([
                            'entity_type' => 'client',
                            'entity_id'   => $clientId,
                            'type'        => 'debit', // Debit increases debt
                            'amount'      => $total,
                            'description' => 'Compra #' . $saleId,
                            'reference_id'=> $saleId,
                            'created_at'  => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
            
            if ($this->db->transStatus() === false) {
                 $this->db->transRollback();
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Error en transacción']);
            } else {
                 $this->db->transCommit();
                 return $this->response->setJSON(['status' => 'success', 'sale_id' => $saleId]);
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function getDetails($id)
    {
        $sale = $this->saleModel->find($id);
        if (!$sale) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Venta no encontrada']);
        }

        $items = $this->saleItemModel
            ->select('sale_items.*, products.name as product_name, products.code as product_code')
            ->join('products', 'products.id = sale_items.product_id', 'left')
            ->where('sale_id', $id)
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'sale' => $sale,
            'items' => $items
        ]);
    }
}
