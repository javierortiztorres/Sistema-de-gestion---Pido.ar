<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Helper function to get Category ID by name
        $getCatId = function($name) use ($db) {
            $cat = $db->table('categories')->getWhere(['name' => $name])->getRow();
            return $cat ? $cat->id : null;
        };

        // Get Category IDs
        $catDetergente   = $getCatId('Detergentes');
        $catDesinfectante= $getCatId('Desinfectantes');
        $catAccesorios   = $getCatId('Accesorios');
        $catPerros       = $getCatId('Perros');
        $catGatos        = $getCatId('Gatos');

        $products = [
            // Limpieza - Detergentes
            [
                'code'           => 'LIMP001',
                'category_id'    => $catDetergente,
                'name'           => 'Detergente Magistral Limón 750ml',
                'stock_quantity' => 50,
                'cost_price'     => 1200.00,
                'retail_price'   => 1800.00,
                'wholesale_price'=> 1600.00,
            ],
            [
                'code'           => 'LIMP002',
                'category_id'    => $catDetergente,
                'name'           => 'Detergente Ala Ultra 500ml',
                'stock_quantity' => 40,
                'cost_price'     => 900.00,
                'retail_price'   => 1400.00,
                'wholesale_price'=> 1200.00,
            ],
            // Limpieza - Desinfectantes
            [
                'code'           => 'LIMP003',
                'category_id'    => $catDesinfectante,
                'name'           => 'Lavandina Ayudín Original 1L',
                'stock_quantity' => 100,
                'cost_price'     => 800.00,
                'retail_price'   => 1200.00,
                'wholesale_price'=> 1000.00,
            ],
            [
                'code'           => 'LIMP004',
                'category_id'    => $catDesinfectante,
                'name'           => 'Desinfectante Lysoform Aerosol 360cc',
                'stock_quantity' => 24,
                'cost_price'     => 2500.00,
                'retail_price'   => 3500.00,
                'wholesale_price'=> 3100.00,
            ],
            [
                'code'           => 'LIMP005',
                'category_id'    => $catDesinfectante,
                'name'           => 'Limpiador Piso Poett Lavanda 1.8L',
                'stock_quantity' => 30,
                'cost_price'     => 1800.00,
                'retail_price'   => 2600.00,
                'wholesale_price'=> 2300.00,
            ],
            // Limpieza - Accesorios
            [
                'code'           => 'LIMP006',
                'category_id'    => $catAccesorios,
                'name'           => 'Escoba Plástica Reforzada',
                'stock_quantity' => 15,
                'cost_price'     => 3000.00,
                'retail_price'   => 4500.00,
                'wholesale_price'=> 4000.00,
            ],
            [
                'code'           => 'LIMP007',
                'category_id'    => $catAccesorios,
                'name'           => 'Esponja Mortimer Multiuso x3',
                'stock_quantity' => 60,
                'cost_price'     => 500.00,
                'retail_price'   => 800.00,
                'wholesale_price'=> 700.00,
            ],
            // Alimentos Balanceados - Perros
            [
                'code'           => 'ALIM001',
                'category_id'    => $catPerros,
                'name'           => 'Pedigree Adulto Carne 15kg',
                'stock_quantity' => 10,
                'cost_price'     => 25000.00,
                'retail_price'   => 35000.00,
                'wholesale_price'=> 32000.00,
            ],
            [
                'code'           => 'ALIM002',
                'category_id'    => $catPerros,
                'name'           => 'Dog Chow Cachorros 3kg',
                'stock_quantity' => 20,
                'cost_price'     => 8000.00,
                'retail_price'   => 12000.00,
                'wholesale_price'=> 10500.00,
            ],
            [
                'code'           => 'ALIM003',
                'category_id'    => $catPerros,
                'name'           => 'Royal Canin Mini Adult 7.5kg',
                'stock_quantity' => 5,
                'cost_price'     => 45000.00,
                'retail_price'   => 60000.00,
                'wholesale_price'=> 55000.00,
            ],
            [
                'code'           => 'ALIM004',
                'category_id'    => $catPerros,
                'name'           => 'Excellent Adulto Chicken & Rice 15kg',
                'stock_quantity' => 8,
                'cost_price'     => 38000.00,
                'retail_price'   => 52000.00,
                'wholesale_price'=> 48000.00,
            ],
            // Alimentos Balanceados - Gatos
            [
                'code'           => 'ALIM005',
                'category_id'    => $catGatos,
                'name'           => 'Whiskas Adulto Pescado 10kg',
                'stock_quantity' => 12,
                'cost_price'     => 22000.00,
                'retail_price'   => 30000.00,
                'wholesale_price'=> 28000.00,
            ],
            [
                'code'           => 'ALIM006',
                'category_id'    => $catGatos,
                'name'           => 'Cat Chow Gatitos 1kg',
                'stock_quantity' => 25,
                'cost_price'     => 4000.00,
                'retail_price'   => 6000.00,
                'wholesale_price'=> 5500.00,
            ],
            [
                'code'           => 'ALIM007',
                'category_id'    => $catGatos,
                'name'           => 'Royal Canin Urinary S/O 2kg',
                'stock_quantity' => 6,
                'cost_price'     => 15000.00,
                'retail_price'   => 21000.00,
                'wholesale_price'=> 19000.00,
            ],
            [
                'code'           => 'ALIM008',
                'category_id'    => $catGatos,
                'name'           => 'Sabrositos Mix de Carnes 3kg',
                'stock_quantity' => 15,
                'cost_price'     => 5000.00,
                'retail_price'   => 7500.00,
                'wholesale_price'=> 6800.00,
            ],
        ];

        foreach ($products as $prod) {
            $data = array_merge($prod, [
                'description' => $prod['name'],
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $this->db->table('products')->insert($data);
        }
    }
}
