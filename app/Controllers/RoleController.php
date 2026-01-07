<?php

namespace App\Controllers;

use App\Models\RoleModel;

class RoleController extends BaseController
{
    protected $roleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $data['roles'] = $this->roleModel->findAll();
        return view('roles/index', $data);
    }

    public function create()
    {
        return view('roles/create');
    }

    public function store()
    {
        if (!$this->validate($this->roleModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->roleModel->save([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('roles')->with('message', 'Rol creado exitosamente.');
    }

    public function edit($id)
    {
        $data['role'] = $this->roleModel->find($id);
        if (!$data['role']) {
            return redirect()->to('roles')->with('error', 'Rol no encontrado.');
        }
        return view('roles/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate($this->roleModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->roleModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('roles')->with('message', 'Rol actualizado exitosamente.');
    }

    public function delete($id)
    {
        // Check if attached to users
        $userModel = new \App\Models\UserModel();
        if ($userModel->where('role_id', $id)->countAllResults() > 0) {
            return redirect()->to('roles')->with('error', 'No se puede eliminar un rol asignado a usuarios.');
        }

        $this->roleModel->delete($id);
        return redirect()->to('roles')->with('message', 'Rol eliminado exitosamente.');
    }

    public function exportCsv()
    {
        $filename = 'roles_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $roles = $this->roleModel->findAll();
        $output = fopen('php://output', 'w');

        fputcsv($output, ['ID', 'Nombre', 'Descripcion', 'Creado']);

        foreach ($roles as $role) {
            fputcsv($output, [
                $role['id'],
                $role['name'],
                $role['description'],
                $role['created_at'],
            ]);
        }
        fclose($output);
        exit;
    }

    public function importCsv()
    {
        return view('roles/import');
    }

    public function processImport()
    {
        $file = $this->request->getFile('csv_file');

        if (!$file->isValid() || $file->getExtension() !== 'csv') {
            return redirect()->back()->with('error', 'Por favor suba un archivo CSV válido.');
        }

        if (($handle = fopen($file->getTempName(), "r")) !== FALSE) {
            $header = fgetcsv($handle); // Skip header
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Expected: Name, Description
                if (count($data) >= 1) { // Minimal validation
                     $existing = $this->roleModel->where('name', $data[0])->first();
                     if (!$existing) {
                        $this->roleModel->insert([
                            'name'        => $data[0],
                            'description' => $data[1] ?? '',
                        ]);
                     }
                }
            }
            fclose($handle);
        }

        return redirect()->to('roles')->with('message', 'Importación completada.');
    }
}
