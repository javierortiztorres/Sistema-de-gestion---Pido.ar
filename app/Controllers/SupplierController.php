<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupplierModel;

class SupplierController extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    public function index()
    {
        $data['suppliers'] = $this->supplierModel->findAll();
        return view('suppliers/index', $data);
    }

    public function create()
    {
        return view('suppliers/create');
    }

    public function store()
    {
        if (!$this->validate($this->supplierModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->supplierModel->insert([
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        ]);

        return redirect()->to('suppliers')->with('message', 'Proveedor creado exitosamente.');
    }

    public function edit($id)
    {
        $data['supplier'] = $this->supplierModel->find($id);
        if (!$data['supplier']) {
            return redirect()->to('suppliers')->with('error', 'Proveedor no encontrado.');
        }
        return view('suppliers/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate($this->supplierModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->supplierModel->update($id, [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
        ]);

        return redirect()->to('suppliers')->with('message', 'Proveedor actualizado exitosamente.');
    }

    public function delete($id)
    {
        $this->supplierModel->delete($id);
        return redirect()->to('suppliers')->with('message', 'Proveedor eliminado.');
    }

    public function exportCsv()
    {
        $filename = 'proveedores_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $suppliers = $this->supplierModel->findAll();
        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, ['ID', 'Nombre', 'Email', 'Telefono', 'Saldo Cta Cte']);

        foreach ($suppliers as $supplier) {
            fputcsv($output, [
                $supplier['id'],
                $supplier['name'],
                $supplier['email'],
                $supplier['phone'],
                $supplier['account_balance']
            ]);
        }
        fclose($output);
        exit;
    }

    public function importCsv()
    {
        return view('suppliers/import');
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
                // Expected: Name, Email, Phone
                if (count($data) >= 1) { 
                     $exists = false;
                     if (!empty($data[1])) {
                        $exists = $this->supplierModel->where('email', $data[1])->first();
                     }

                     if (!$exists) {
                        $this->supplierModel->insert([
                            'name'  => $data[0],
                            'email' => $data[1] ?? null,
                            'phone' => $data[2] ?? null,
                        ]);
                     }
                }
            }
            fclose($handle);
        }

        return redirect()->to('suppliers')->with('message', 'Importación completada.');
    }
}
