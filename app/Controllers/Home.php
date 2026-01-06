<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ProductModel;
use App\Models\SaleModel;

class Home extends BaseController
{
    public function index()
    {
        $clientModel  = new ClientModel();
        $productModel = new ProductModel();
        $saleModel    = new SaleModel();

        $data = [];

        // Stats
        $data['totalClients']  = $clientModel->countAllResults();
        $data['totalProducts'] = $productModel->countAllResults();
        $data['totalSales']    = $saleModel->countAllResults();
        
        // Low Stock Count
        $data['lowStockCount'] = $productModel->where('stock_quantity <= min_stock')->countAllResults();

        // Today's Sales
        $today = date('Y-m-d');
        $data['todaySales'] = $saleModel->where('DATE(created_at)', $today)->countAllResults();

        return view('dashboard', $data);
    }
}
