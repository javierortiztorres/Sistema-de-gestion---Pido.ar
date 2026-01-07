<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new \App\Models\CategoryModel();
    }

    public function index()
    {
        $data['products'] = $this->productModel
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->orderBy('categories.name', 'ASC')
            ->orderBy('products.name', 'ASC')
            ->findAll();
            
        return view('products/index', $data);
    }

    public function create()
    {
        $data['categories'] = $this->categoryModel->findAll();
        return view('products/create', $data);
    }

    public function store()
    {
        $rules = [
            'category_id'     => 'permit_empty|integer',
            'code'            => 'required|max_length[50]|is_unique[products.code]',
            'name'            => 'required|min_length[3]|max_length[255]',
            'cost_price'      => 'required|decimal',
            'retail_price'    => 'required|decimal',
            'wholesale_price' => 'required|decimal',
            'stock_quantity'  => 'required|integer',
            'min_stock'       => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->productModel->insert([
            'category_id'     => $this->request->getPost('category_id'),
            'code'            => $this->request->getPost('code'),
            'name'            => $this->request->getPost('name'),
            'description'     => $this->request->getPost('description'),
            'cost_price'      => $this->request->getPost('cost_price'),
            'retail_price'    => $this->request->getPost('retail_price'),
            'wholesale_price' => $this->request->getPost('wholesale_price'),
            'stock_quantity'  => $this->request->getPost('stock_quantity'),
            'min_stock'       => $this->request->getPost('min_stock'),
        ]);

        return redirect()->to('/products')->with('message', 'Product created successfully');
    }

    public function edit($id)
    {
        $data['product']    = $this->productModel->find($id);
        $data['categories'] = $this->categoryModel->findAll();
        
        if (empty($data['product'])) {
            return redirect()->to('/products')->with('error', 'Product not found');
        }
        return view('products/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'category_id'     => 'permit_empty|integer',
            'code'            => "required|max_length[50]|is_unique[products.code,id,{$id}]",
            'name'            => 'required|min_length[3]|max_length[255]',
            'cost_price'      => 'required|decimal',
            'retail_price'    => 'required|decimal',
            'wholesale_price' => 'required|decimal',
            'stock_quantity'  => 'required|integer',
            'min_stock'       => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->productModel->update($id, [
            'category_id'     => $this->request->getPost('category_id'),
            'code'            => $this->request->getPost('code'),
            'name'            => $this->request->getPost('name'),
            'description'     => $this->request->getPost('description'),
            'cost_price'      => $this->request->getPost('cost_price'),
            'retail_price'    => $this->request->getPost('retail_price'),
            'wholesale_price' => $this->request->getPost('wholesale_price'),
            'stock_quantity'  => $this->request->getPost('stock_quantity'),
            'min_stock'       => $this->request->getPost('min_stock'),
        ]);

        return redirect()->to('/products')->with('message', 'Product updated successfully');
    }

    public function delete($id)
    {
        $this->productModel->delete($id);
        return redirect()->to('/products')->with('message', 'Product deleted successfully');
    }

    public function adjustStock($id)
    {
        $adjustment = $this->request->getPost('adjustment');
        $reason     = $this->request->getPost('reason');
        $product    = $this->productModel->find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Producto no encontrado');
        }

        $newStock = $product['stock_quantity'] + $adjustment;
        if ($newStock < 0) {
            return redirect()->back()->with('error', 'El stock no puede ser negativo');
        }

        // Update Product
        $this->productModel->update($id, ['stock_quantity' => $newStock]);

        // Log
        $logModel = new \App\Models\StockLogModel();
        $logModel->insert([
            'product_id' => $id,
            'change_amount' => $adjustment,
            'reason' => $reason,
            'user_id' => session()->get('user_id'), 
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('message', 'Stock ajustado correctamente');
    }

    public function exportCsv()
    {
        $filename = 'productos_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $products = $this->productModel
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->findAll();

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, ['ID', 'Codigo', 'Nombre', 'Categoria', 'Precio Minorista', 'Precio Mayorista', 'Stock']);

        foreach ($products as $product) {
            fputcsv($output, [
                $product['id'],
                $product['code'],
                $product['name'],
                $product['category_name'],
                $product['retail_price'],
                $product['wholesale_price'],
                $product['stock_quantity']
            ]);
        }
        fclose($output);
        exit;
    }

    public function importCsv()
    {
        return view('products/import');
    }

    public function processImport()
    {
        $file = $this->request->getFile('csv_file');

        if (!$file->isValid() || $file->getExtension() !== 'csv') {
            return redirect()->back()->with('error', 'Por favor suba un archivo CSV válido.');
        }

        if (($handle = fopen($file->getTempName(), "r")) !== FALSE) {
            fgetcsv($handle); // Skip header
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Expected: Code, Name, CategoryName, CostPrice, RetailPrice, WholesalePrice, Stock, MinStock
                if (count($data) >= 7) { 
                     // Check existing via code
                     $existing = $this->productModel->where('code', $data[0])->first();
                     if (!$existing) {
                        // Find Category ID by Name
                        $categoryId = null;
                        if (!empty($data[2])) {
                            $cat = $this->categoryModel->where('name', $data[2])->first();
                            if ($cat) {
                                $categoryId = $cat['id'];
                            } else {
                                // Optional: Create Category if not exists
                                $categoryId = $this->categoryModel->insert(['name' => $data[2]]);
                            }
                        }

                        $this->productModel->insert([
                            'code'            => $data[0],
                            'name'            => $data[1],
                            'category_id'     => $categoryId,
                            'cost_price'      => $data[3] ?? 0,
                            'retail_price'    => $data[4] ?? 0,
                            'wholesale_price' => $data[5] ?? 0,
                            'stock_quantity'  => $data[6] ?? 0,
                            'min_stock'       => $data[7] ?? 0,
                        ]);
                     }
                }
            }
            fclose($handle);
        }

        return redirect()->to('products')->with('message', 'Importación completada.');
    }
}
