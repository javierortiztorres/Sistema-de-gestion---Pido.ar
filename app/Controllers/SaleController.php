<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use App\Models\CategoryModel;

class SaleController extends BaseController
{
    protected $saleModel;
    protected $saleItemModel;
    protected $productModel;
    protected $clientModel;
    protected $categoryModel; // Added
    protected $db;

    public function __construct()
    {
        $this->saleModel     = new SaleModel();
        $this->saleItemModel = new SaleItemModel();
        $this->productModel  = new ProductModel();
        $this->clientModel   = new ClientModel();
        $this->categoryModel = new CategoryModel(); // Added
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
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                 $dbError = $this->db->error();
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Transaction failed: ' . json_encode($dbError)]);
            }

            return $this->response->setJSON(['status' => 'success', 'sale_id' => $saleId]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
