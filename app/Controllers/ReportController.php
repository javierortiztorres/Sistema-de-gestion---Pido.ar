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
        $dompdf->stream("invoice_{$saleId}.pdf", ["Attachment" => false]);
    }
}
