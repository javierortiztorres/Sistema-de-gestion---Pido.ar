<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ConsumidorFinalSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'    => 'Consumidor Final',
            'type'    => 'retail',
            'email'   => 'consumidor@final.com', // Dummy unique email
            'phone'   => '',
            'address' => '',
            'account_balance' => 0.00
        ];

        // Check if exists
        $exists = $this->db->table('clients')->where('name', 'Consumidor Final')->countAllResults();

        if (!$exists) {
            $this->db->table('clients')->insert($data);
        }
    }
}
