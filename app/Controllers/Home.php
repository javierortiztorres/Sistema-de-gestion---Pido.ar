<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ProductModel;
use App\Models\SaleModel;
use App\Models\SupplierModel;

class Home extends BaseController
{
    public function index()
    {
        $clientModel  = new ClientModel();
        $productModel = new ProductModel();
        $saleModel    = new SaleModel();
        $supplierModel = new SupplierModel();

        $data = [];

        // Stats
        $data['totalClients']  = $clientModel->countAllResults();
        $data['totalProducts'] = $productModel->countAllResults();
        $data['totalSales']    = $saleModel->countAllResults();
        $data['totalSuppliers'] = $supplierModel->countAllResults();
        
        // Low Stock Count
        $data['lowStockCount'] = $productModel->where('stock_quantity <= min_stock')->countAllResults();

        // Today's Sales
        $today = date('Y-m-d');
        $data['todaySales'] = $saleModel->where('DATE(created_at)', $today)->countAllResults();

        // Financials
        // Clients owe us (Receivables) - Sum of positive balances
        $clients = $clientModel->findAll();
        $totalReceivables = 0;
        foreach ($clients as $c) {
            $totalReceivables += $c['account_balance'];
        }
        $data['totalReceivables'] = $totalReceivables;

        // We owe suppliers (Payables) - Sum of positive balances (assuming positive = debt)
        $suppliers = $supplierModel->findAll();
        $totalPayables = 0;
        foreach ($suppliers as $s) {
            $totalPayables += $s['account_balance'];
        }
        $data['totalPayables'] = $totalPayables;

        return view('dashboard', $data);
    }
}
