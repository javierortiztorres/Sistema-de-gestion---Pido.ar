<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'employee'],
                'default'    => 'employee',
                'after'      => 'password_hash',
            ],
        ];
        $this->forge->addColumn('users', $fields);

        // Update existing admin user to have 'admin' role
        $this->db->table('users')->where('id', 1)->update(['role' => 'admin']);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'role');
    }
}
