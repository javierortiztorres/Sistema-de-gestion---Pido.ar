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
        return redirect()->to('categories')->with('message', 'Categoría eliminada.');
    }

    public function exportCsv()
    {
        $filename = 'categorias_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $categories = $this->categoryModel->findAll();
        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, ['ID', 'Nombre', 'Descripcion']);

        foreach ($categories as $category) {
            fputcsv($output, [
                $category['id'],
                $category['name'],
                $category['description']
            ]);
        }
        fclose($output);
        exit;
    }

    public function importCsv()
    {
        return view('categories/import');
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
                // Expected: Name, Description
                if (count($data) >= 1) { 
                     $existing = $this->categoryModel->where('name', $data[0])->first();
                     if (!$existing) {
                        $this->categoryModel->insert([
                            'name'        => $data[0],
                            'description' => $data[1] ?? '',
                        ]);
                     }
                }
            }
            fclose($handle);
        }

        return redirect()->to('categories')->with('message', 'Importación completada.');
    }
}
