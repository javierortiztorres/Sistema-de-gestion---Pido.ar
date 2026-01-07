<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('users/index', $data);
    }

    public function create()
    {
        $roleModel = new \App\Models\RoleModel();
        $data['roles'] = $roleModel->findAll();
        return view('users/create', $data);
    }

    public function store()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role_id'  => 'required|integer|is_not_unique[roles.id]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'name'          => $this->request->getPost('name'),
            'email'         => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role_id'       => $this->request->getPost('role_id'),
        ]);

        return redirect()->to('users')->with('message', 'Usuario creado exitosamente.');
    }

    public function edit($id)
    {
        $data['user'] = $this->userModel->find($id);
        
        $roleModel = new \App\Models\RoleModel();
        $data['roles'] = $roleModel->findAll();

        if (empty($data['user'])) {
            return redirect()->to('users')->with('error', 'Usuario no encontrado');
        }
        return view('users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('users')->with('error', 'Usuario no encontrado.');
        }

        $rules = [
            'name'  => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'  => 'required|in_list[admin,employee]',
        ];

        // Validar password solo si se envia
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role'  => $this->request->getPost('role'),
        ];

        if (!empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to('users')->with('message', 'Usuario actualizado exitosamente.');
    }

    public function delete($id)
    {
        if ($id == session()->get('id')) {
             return redirect()->to('users')->with('error', 'No puedes eliminar tu propio usuario.');
        }
        
        $this->userModel->delete($id);
        return redirect()->to('users')->with('message', 'Usuario eliminado.');
    }

    public function exportCsv()
    {
        $filename = 'usuarios_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $users = $this->userModel->findAll();
        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, ['ID', 'Nombre', 'Email', 'Role ID', 'Fecha Creacion']);

        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['name'],
                $user['email'],
                $user['role_id'],
                $user['created_at']
            ]);
        }
        fclose($output);
        exit;
    }

    public function importCsv()
    {
        return view('users/import');
    }

    public function processImport()
    {
        $file = $this->request->getFile('csv_file');

        if (!$file->isValid() || $file->getExtension() !== 'csv') {
            return redirect()->back()->with('error', 'Por favor suba un archivo CSV válido.');
        }

        if (($handle = fopen($file->getTempName(), "r")) !== FALSE) {
            fgetcsv($handle); // Skip header
            $roleModel = new \App\Models\RoleModel();

            // Cache roles for performance
            $allRoles = $roleModel->findAll();
            $rolesMap = []; // Name -> ID
            foreach($allRoles as $r) {
                $rolesMap[strtolower($r['name'])] = $r['id'];
            }
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Expected: Name, Email, Password, Role Name
                if (count($data) >= 4) { 
                     $exists = $this->userModel->where('email', $data[1])->first();
                     if (!$exists) {
                        $roleName = strtolower(trim($data[3]));
                        $roleId = $rolesMap[$roleName] ?? null;

                        // Default to employee if role not found, or skip? Let's use ID 2 (Employee) as fallback or create if vital
                        // For safety, if role invalid, skip or default. Let's try to map 'admin' and 'employee'
                        if (!$roleId) {
                            // Try searching by exact ID if number
                             if (is_numeric($roleName)) {
                                 $roleId = $roleName;
                             } else {
                                $roleId = 2; // Fallback to assumed 'employee' ID
                             }
                        }

                        $this->userModel->insert([
                            'name'          => $data[0],
                            'email'         => $data[1],
                            'password_hash' => password_hash($data[2], PASSWORD_DEFAULT),
                            'role_id'       => $roleId,
                        ]);
                     }
                }
            }
            fclose($handle);
        }

        return redirect()->to('users')->with('message', 'Importación completada.');
    }
}
