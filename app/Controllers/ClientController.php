<?php

namespace App\Controllers;

use App\Models\ClientModel;

class ClientController extends BaseController
{
    protected $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    public function index()
    {
        $data['clients'] = $this->clientModel->findAll();
        return view('clients/index', $data);
    }

    public function create()
    {
        return view('clients/create');
    }

    public function store()
    {
        $rules = [
            'name'  => 'required|min_length[3]|max_length[255]',
            'type'  => 'required|in_list[retail,wholesale]',
            'email' => 'permit_empty|valid_email|max_length[255]',
            'phone' => 'permit_empty|max_length[20]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->clientModel->insert([
            'name'    => $this->request->getPost('name'),
            'type'    => $this->request->getPost('type'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ]);

        return redirect()->to('/clients')->with('message', 'Client created successfully');
    }

    public function edit($id)
    {
        $data['client'] = $this->clientModel->find($id);
        if (empty($data['client'])) {
            return redirect()->to('/clients')->with('error', 'Client not found');
        }
        return view('clients/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'name'  => 'required|min_length[3]|max_length[255]',
            'type'  => 'required|in_list[retail,wholesale]',
            'email' => 'permit_empty|valid_email|max_length[255]',
            'phone' => 'permit_empty|max_length[20]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->clientModel->update($id, [
            'name'    => $this->request->getPost('name'),
            'type'    => $this->request->getPost('type'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ]);

        return redirect()->to('/clients')->with('message', 'Client updated successfully');
    }

    public function delete($id)
    {
        $this->clientModel->delete($id);
        return redirect()->to('clients')->with('message', 'Cliente eliminado.');
    }

    public function exportCsv()
    {
        $filename = 'clientes_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $clients = $this->clientModel->findAll();
        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, ['ID', 'Nombre', 'Tipo', 'Email', 'Telefono', 'Direccion', 'Saldo Cta Cte']);

        foreach ($clients as $client) {
            fputcsv($output, [
                $client['id'],
                $client['name'],
                $client['type'],
                $client['email'],
                $client['phone'],
                $client['address'],
                $client['account_balance']
            ]);
        }
        fclose($output);
        exit;
    }
}
