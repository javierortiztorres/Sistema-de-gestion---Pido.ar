<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $this->db->disableForeignKeyChecks();
        $this->db->table('categories')->truncate();
        $this->db->enableForeignKeyChecks();

        // Limpieza
        $this->db->table('categories')->insert([
            'name'        => 'Limpieza',
            'description' => 'Artículos de limpieza general',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        $limpiezaId = $this->db->insertID();

        $configLimpieza = [
            ['name' => 'Detergentes', 'desc' => 'Detergentes líquidos y en polvo'],
            ['name' => 'Desinfectantes', 'desc' => 'Lavandinas y desinfectantes'],
            ['name' => 'Accesorios', 'desc' => 'Escobas, trapos, esponjas'],
        ];

        foreach ($configLimpieza as $sub) {
            $this->db->table('categories')->insert([
                'parent_id'   => $limpiezaId,
                'name'        => $sub['name'],
                'description' => $sub['desc'],
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        // Alimentos Balanceados
        $this->db->table('categories')->insert([
            'name'        => 'Alimentos Balanceados',
            'description' => 'Comida para mascotas',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        $alimentosId = $this->db->insertID();

        $configAlimentos = [
            ['name' => 'Perros', 'desc' => 'Alimento seco y húmedo para perros'],
            ['name' => 'Gatos', 'desc' => 'Alimento seco y húmedo para gatos'],
            ['name' => 'Aves', 'desc' => 'Semillas y mezclas para aves'],
        ];

        foreach ($configAlimentos as $sub) {
            $this->db->table('categories')->insert([
                'parent_id'   => $alimentosId,
                'name'        => $sub['name'],
                'description' => $sub['desc'],
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
