<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentMethod extends Migration
{
    public function up()
    {
        $this->forge->addColumn('sales', [
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'cash',
                'after'      => 'discount'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sales', 'payment_method');
    }
}
