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
        $movTypeReq = $this->request->getPost('mov_type') ?? 'payment'; // Default to payment for backward compat

        if ($amount <= 0) {
            return redirect()->back()->with('error', 'El monto debe ser mayor a 0.');
        }

        // Determine Entity Model
        $model = ($type === 'client') ? $this->clientModel : $this->supplierModel;
        $entity = $model->find($id);

        if (!$entity) {
            return redirect()->back()->with('error', 'Entidad no encontrada.');
        }

        $newBalance = $entity['account_balance'];
        $dbMovType = '';

        // Logic for Client:
        // Balance = DEBT.
        // Payment: Reduces Balance (Type: credit)
        // Debt/Charge: Increases Balance (Type: debit)
        
        // Logic for Supplier:
        // Balance = DEBT (We owe them).
        // Payment: Reduces Balance (Type: debit) - Wait, standard accounting: Supplier Credit increases Payables. Payment (Debit) reduces it.
        // Debt/Charge (We buy on credit): Increases Balance (Type: credit).

        if ($type === 'client') {
            if ($movTypeReq === 'payment') {
                $newBalance -= $amount;
                $dbMovType = 'credit';
            } else { // debt
                $newBalance += $amount;
                $dbMovType = 'debit';
            }
        } else { // supplier
            if ($movTypeReq === 'payment') { // We pay them
                $newBalance -= $amount;
                $dbMovType = 'debit'; 
            } else { // They charge us (e.g. initial balance)
                $newBalance += $amount;
                $dbMovType = 'credit';
            }
        }

        // Update Entity
        $model->update($id, ['account_balance' => $newBalance]);

        // Log Movement
        $this->movementModel->insert([
            'entity_type' => $type,
            'entity_id'   => $id,
            'type'        => $dbMovType,
            'amount'      => $amount,
            'description' => $description ?: ($movTypeReq === 'payment' ? 'Pago registrado' : 'Cargo / Ajuste'),
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('message', 'Movimiento registrado exitosamente.');
    }
}
