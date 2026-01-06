<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/');
        }
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $session = session();
        $model   = new UserModel();
        $email   = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user) {
            if (password_verify($password, $user['password_hash'])) {
                $ses_data = [
                    'id'       => $user['id'],
                    'name'     => $user['name'],
                    'email'    => $user['email'],
                    'is_logged_in' => true,
                ];
                $session->set($ses_data);
                return redirect()->to('/');
            } else {
                $session->setFlashdata('error', 'Contraseña incorrecta.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Email no encontrado.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function profile()
    {
        return view('auth/profile');
    }

    public function updateProfile()
    {
        $session = session();
        $model   = new UserModel();
        $id      = $session->get('id');
        $user    = $model->find($id);

        if (!$user) {
            return redirect()->to('/login');
        }

        // Verify current password
        $currentPassword = $this->request->getPost('current_password');
        if (!password_verify($currentPassword, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'La contraseña actual es incorrecta.');
        }

        $rules = [
            'name' => 'required|min_length[3]',
        ];

        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
             return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
        ];

        if (!empty($newPassword)) {
            $data['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);
        
        // Update session name
        $session->set('name', $data['name']);

        return redirect()->to('auth/profile')->with('message', 'Perfil actualizado correctamente.');
    }
}
