<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StockLogModel;

class StockLogController extends BaseController
{
    protected $stockLogModel;

    public function __construct()
    {
        $this->stockLogModel = new StockLogModel();
    }

    public function index()
    {
        // Join with products and users to get readable names
        $data['logs'] = $this->stockLogModel
            ->select('stock_logs.*, products.name as product_name, products.code as product_code, users.name as user_name')
            ->join('products', 'products.id = stock_logs.product_id', 'left')
            ->join('users', 'users.id = stock_logs.user_id', 'left')
            ->orderBy('stock_logs.created_at', 'DESC')
            ->findAll();

        return view('stock_logs/index', $data);
    }

    public function exportCsv()
    {
        $filename = 'movimientos_stock_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $logModel = new StockLogModel();
        $logs = $logModel
            ->select('stock_logs.*, products.name as product_name, users.name as user_name')
            ->join('products', 'products.id = stock_logs.product_id', 'left')
            ->join('users', 'users.id = stock_logs.user_id', 'left')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, ['ID', 'Fecha', 'Producto', 'Usuario', 'Cambio', 'Razon']);

        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['created_at'],
                $log['product_name'],
                $log['user_name'],
                $log['change_amount'],
                $log['reason']
            ]);
        }
        fclose($output);
        exit;
    }
}
