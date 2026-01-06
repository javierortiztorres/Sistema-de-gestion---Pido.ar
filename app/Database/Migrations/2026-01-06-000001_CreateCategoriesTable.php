<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        // Categories Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('categories');

        // Add Category ID to Products
        $this->forge->addColumn('products', [
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id'
            ]
        ]);

        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'CASCADE', 'products_category_id_foreign');
        $this->forge->processIndexes('products');
    }

    public function down()
    {
        $this->forge->dropForeignKey('products', 'products_category_id_foreign');
        $this->forge->dropColumn('products', 'category_id');
        $this->forge->dropTable('categories');
    }
}
