<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUsersTableForRoles extends Migration
{
    public function up()
    {
        // Add role_id column
        $this->forge->addColumn('users', [
            'role_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Temporarily null to migrate data
                'after'      => 'password_hash'
            ]
        ]);

        // Migrate existing data (Map enum 'role' to 'role_id')
        $this->db->query("UPDATE users SET role_id = (SELECT id FROM roles WHERE name = users.role)");

        // Now drop the old enum column
        $this->forge->dropColumn('users', 'role');
    }

    public function down()
    {
        // Add back the enum column
        $this->forge->addColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'employee'],
                'default'    => 'employee',
                'after'      => 'password_hash'
            ]
        ]);

        // Restore data (Map 'role_id' back to enum 'role')
        $this->db->query("UPDATE users SET role = (SELECT name FROM roles WHERE id = users.role_id)");

        // Drop role_id
        $this->forge->dropColumn('users', 'role_id');
    }
}
