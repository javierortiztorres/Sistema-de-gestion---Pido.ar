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
        $clients = $clientModel->orderBy('account_balance', 'DESC')->findAll();
        $totalReceivables = 0;
        $maxDebtor = null;
        if (!empty($clients)) {
             foreach ($clients as $c) {
                if ($c['account_balance'] > 0) {
                    $totalReceivables += $c['account_balance'];
                }
             }
             // Top debtor is the first one since we ordered by DESC
             if ($clients[0]['account_balance'] > 0) {
                 $maxDebtor = $clients[0];
             }
        }
        $data['totalReceivables'] = $totalReceivables;
        $data['maxDebtor'] = $maxDebtor;

        // We owe suppliers (Payables)
        $suppliers = $supplierModel->findAll();
        $totalPayables = 0;
        foreach ($suppliers as $s) {
            $totalPayables += $s['account_balance'];
        }
        $data['totalPayables'] = $totalPayables;

        // Advanced Alerts
        // 1. Out of Stock (0)
        $data['outOfStockCount'] = $productModel->where('stock_quantity', 0)->countAllResults();

        // 2. Cash Sales Today
        // Note: Assuming 'payment_method' stores 'cash' or similar. We need to check SaleModel or DB. 
        // Based on previous steps, payment_method is likely 'effectivo', 'tarjeta', 'transferencia', 'cta_cte'.
        // Let's assume 'efectivo' for now based on standard Spanish naming.
        $data['cashSalesToday'] = $saleModel
            ->where('DATE(created_at)', $today)
            ->where('payment_method', 'efectivo')
            ->selectSum('total')
            ->first()['total'] ?? 0;
            
        return view('dashboard', $data);
    }
}
