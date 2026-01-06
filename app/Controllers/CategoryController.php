<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        // Join with self to get parent name
        $data['categories'] = $this->categoryModel
            ->select('categories.*, parent.name as parent_name')
            ->join('categories as parent', 'parent.id = categories.parent_id', 'left')
            ->findAll();
            
        return view('categories/index', $data);
    }

    public function create()
    {
        $data['categories'] = $this->categoryModel->findAll(); // For parent dropdown
        return view('categories/create', $data);
    }

    public function store()
    {
        $rules = [
            'parent_id' => 'permit_empty|integer',
            'name'      => 'required|min_length[3]|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->insert([
            'parent_id'   => $this->request->getPost('parent_id') ?: null,
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/categories')->with('message', 'Category created successfully');
    }

    public function edit($id)
    {
        $data['category'] = $this->categoryModel->find($id);
        
        // Fetch all categories except self (to avoid circular parent)
        $data['categories'] = $this->categoryModel->where('id !=', $id)->findAll();

        if (empty($data['category'])) {
            return redirect()->to('/categories')->with('error', 'Category not found');
        }
        return view('categories/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'parent_id' => 'permit_empty|integer',
            'name'      => 'required|min_length[3]|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->update($id, [
            'parent_id'   => $this->request->getPost('parent_id') ?: null,
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/categories')->with('message', 'Category updated successfully');
    }

    public function delete($id)
    {
        $this->categoryModel->delete($id);
        return redirect()->to('/categories')->with('message', 'Category deleted successfully');
    }
}
