<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountMovementModel extends Model
{
    protected $table            = 'account_movements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['entity_type', 'entity_id', 'type', 'amount', 'description', 'reference_id'];
    protected $useTimestamps    = true;
    protected $updatedField     = ''; // Only created_at needed
}
