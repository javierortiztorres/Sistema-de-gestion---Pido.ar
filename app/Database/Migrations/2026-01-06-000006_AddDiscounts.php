<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiscounts extends Migration
{
    public function up()
    {
        // Add discount to sales table
        $this->forge->addColumn('sales', [
            'discount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'after'      => 'total'
            ]
        ]);

        // Add discount to sale_items table
        $this->forge->addColumn('sale_items', [
            'discount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'after'      => 'price'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sales', 'discount');
        $this->forge->dropColumn('sale_items', 'discount');
    }
}
