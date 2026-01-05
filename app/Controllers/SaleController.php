<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductModel;
use App\Models\ClientModel;

class SaleController extends BaseController
{
    protected $saleModel;
    protected $saleItemModel;
    protected $productModel;
    protected $clientModel;
    protected $db;

    public function __construct()
    {
        $this->saleModel     = new SaleModel();
        $this->saleItemModel = new SaleItemModel();
        $this->productModel  = new ProductModel();
        $this->clientModel   = new ClientModel();
        $this->db            = \Config\Database::connect();
    }

    public function index()
    {
        // For now, redirect to create (POS)
        return redirect()->to('/sales/new');
    }

    public function new()
    {
        $data['clients']  = $this->clientModel->findAll();
        $data['products'] = $this->productModel->findAll(); // In a real app, use AJAX for products
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

        if (empty($items)) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'No items in cart']);
        }

        $this->db->transStart();

        try {
            // Calculate total
            $total = 0;
            foreach ($items as $item) {
                $total += $item->price * $item->quantity;
            }

            // Create Sale
            $saleId = $this->saleModel->insert([
                'client_id' => $clientId,
                'total'     => $total,
                'type'      => $type,
            ]);

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
                ]);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Transaction failed']);
            }

            return $this->response->setJSON(['status' => 'success', 'sale_id' => $saleId]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
