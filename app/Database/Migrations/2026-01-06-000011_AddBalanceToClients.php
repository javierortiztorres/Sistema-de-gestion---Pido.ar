<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBalanceToClients extends Migration
{
    public function up()
    {
        $this->forge->addColumn('clients', [
            'account_balance' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'after'      => 'address',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', 'account_balance');
    }
}
