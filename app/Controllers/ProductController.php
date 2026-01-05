<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $data['products'] = $this->productModel->findAll();
        return view('products/index', $data);
    }

    public function create()
    {
        return view('products/create');
    }

    public function store()
    {
        $rules = [
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
        $data['product'] = $this->productModel->find($id);
        if (empty($data['product'])) {
            return redirect()->to('/products')->with('error', 'Product not found');
        }
        return view('products/edit', $data);
    }

    public function update($id)
    {
        $rules = [
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
}
