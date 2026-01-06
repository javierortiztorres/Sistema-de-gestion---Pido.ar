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
        $rules = [
            'change_amount' => 'required|integer|not_in_list[0]',
            'reason'        => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Invalid adjustment data');
        }

        $changeAmount = $this->request->getPost('change_amount');
        $reason       = $this->request->getPost('reason');
        $userId       = session()->get('id');

        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        // Update Product Stock
        $newStock = $product['stock_quantity'] + $changeAmount;
        $this->productModel->update($id, ['stock_quantity' => $newStock]);

        // Create Log
        $logModel = new \App\Models\StockLogModel();
        $logModel->insert([
            'product_id'    => $id,
            'user_id'       => $userId,
            'change_amount' => $changeAmount,
            'reason'        => $reason,
        ]);

        return redirect()->to('/products')->with('message', 'Stock adjusted successfully');
    }
}
