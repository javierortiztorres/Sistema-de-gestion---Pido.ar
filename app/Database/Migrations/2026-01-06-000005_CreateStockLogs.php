<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'change_amount' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        // Assuming users table exists, but let's make user_id optional/loose to avoid strict dependency issues if seeds fail
        // But since we created users table in Phase 5, we can add FK if we want. Let's stick to simple int for now or loose FK.
        // Let's verify user table. Yes it's there.
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE', 'stock_logs_user_id_fk');
        
        $this->forge->createTable('stock_logs');
    }

    public function down()
    {
        $this->forge->dropTable('stock_logs');
    }
}
