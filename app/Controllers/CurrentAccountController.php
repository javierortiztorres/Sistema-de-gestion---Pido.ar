<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountMovementModel;
use App\Models\ClientModel;
use App\Models\SupplierModel;

class CurrentAccountController extends BaseController
{
    protected $movementModel;
    protected $clientModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->movementModel = new AccountMovementModel();
        $this->clientModel   = new ClientModel();
        $this->supplierModel = new SupplierModel();
    }

    public function view($type, $id)
    {
        if (!in_array($type, ['client', 'supplier'])) {
             throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $entity = null;
        if ($type === 'client') {
            $entity = $this->clientModel->find($id);
        } else {
            $entity = $this->supplierModel->find($id);
        }

        if (!$entity) {
             return redirect()->back()->with('error', 'Entidad no encontrada.');
        }

        $movements = $this->movementModel
            ->where('entity_type', $type)
            ->where('entity_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('current_account/view', [
            'entity' => $entity,
            'type'   => $type,
            'movements' => $movements
        ]);
    }

    public function payment()
    {
        $type = $this->request->getPost('type');
        $id   = $this->request->getPost('entity_id');
        $amount = $this->request->getPost('amount');
        $description = $this->request->getPost('description');

        if ($amount <= 0) {
            return redirect()->back()->with('error', 'El monto debe ser mayor a 0.');
        }

        // Determine Entity Model
        $model = ($type === 'client') ? $this->clientModel : $this->supplierModel;
        $entity = $model->find($id);

        if (!$entity) {
            return redirect()->back()->with('error', 'Entidad no encontrada.');
        }

        // Register Payment (Credit)
        // For Client: Payment decreases debt (Balance decreases if debt is +) or increases credit.
        // Let's assume Balance = Debt. So Payment reduces Balance.
        // Wait, standard: Balance + = Receivable (Client owes us). Payment (Credit) reduces it.
        // For Supplier: Balance + = Payable (We owe Supplier). Payment (Debit) reduces it.
        
        // Let's stick to simple accounting:
        // Client: Debit = Sale (Increases Balance), Credit = Payment (Decreases Balance).
        // Supplier: Credit = Purchase (Increases Balance), Debit = Payment (Decreases Balance).
        
        // BUT for implementation simplicity in DB, let's say 'balance' is always "Amount Owed TO US" (Client) or "Amount We Owe" (Supplier).
        // Client Payment: Type 'credit'. Balance = Balance - Amount.
        // Supplier Payment: Type 'debit'. Balance = Balance - Amount.

        $newBalance = $entity['account_balance'] - $amount;
        $movType = ($type === 'client') ? 'credit' : 'debit'; 

        // Update Entity
        $model->update($id, ['account_balance' => $newBalance]);

        // Log Movement
        $this->movementModel->insert([
            'entity_type' => $type,
            'entity_id'   => $id,
            'type'        => $movType,
            'amount'      => $amount,
            'description' => $description ?: 'Pago registrado',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('message', 'Pago registrado exitosamente.');
    }
}
