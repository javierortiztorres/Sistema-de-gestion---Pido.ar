<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLowStockThreshold extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'min_stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 5,
                'after'      => 'stock_quantity'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'min_stock');
    }
}
