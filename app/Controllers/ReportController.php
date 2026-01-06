<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ClientModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends BaseController
{
    public function invoice($saleId)
    {
        $saleModel     = new SaleModel();
        $saleItemModel = new SaleItemModel();
        $clientModel   = new ClientModel();

        // Fetch Sale Data
        $sale = $saleModel->find($saleId);
        if (!$sale) {
            return redirect()->back()->with('error', 'Sale not found');
        }

        $items  = $saleItemModel
            ->select('sale_items.*, products.name as product_name, products.code as product_code')
            ->join('products', 'products.id = sale_items.product_id', 'left')
            ->where('sale_id', $saleId)
            ->findAll();
            
        $client = null;
        if ($sale['client_id']) {
            $client = $clientModel->find($sale['client_id']);
        }

        // Prepare Data for View
        $data = [
            'sale'   => $sale,
            'items'  => $items,
            'client' => $client,
        ];

        // Generate PDF
        $html = view('sales/invoice', $data);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        // $options->set('isRemoteEnabled', true); // Enable if using remote images

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Stream PDF
        $dompdf->stream('factura_' . $saleId . '.pdf', ['Attachment' => 0]);
    }

    public function dailyCash()
    {
        $saleModel = new \App\Models\SaleModel();
        $today = date('Y-m-d');

        // Total Sales Today
        $totalSales = $saleModel->where('DATE(created_at)', $today)->selectSum('total')->first()['total'] ?? 0;
        
        // Sales Count
        $salesCount = $saleModel->where('DATE(created_at)', $today)->countAllResults();

        // By Payment Method
        $byMethodRaw = $saleModel
            ->select('payment_method, SUM(total) as method_total, COUNT(id) as method_count')
            ->where('DATE(created_at)', $today)
            ->groupBy('payment_method')
            ->findAll();

        // Detailed List
        $sales = $saleModel
            ->select('sales.*, clients.name as client_name')
            ->join('clients', 'clients.id = sales.client_id', 'left')
            ->where('DATE(sales.created_at)', $today)
            ->orderBy('sales.created_at', 'DESC')
            ->findAll();

        $data = [
            'today'       => $today,
            'totalSales'  => $totalSales,
            'salesCount'  => $salesCount,
            'byMethod'    => $byMethodRaw,
            'sales'       => $sales
        ];

        return view('reports/daily_cash', $data);
    }
}
