<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'name' => 'required|is_unique[roles.name,id,{id}]|min_length[3]|max_length[100]',
    ];
}
