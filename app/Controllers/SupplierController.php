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
}
