<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddParentIdToCategories extends Migration
{
    public function up()
    {
        $this->forge->addColumn('categories', [
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'description' // Place after description
            ]
        ]);

        // Self-referencing FK
        $this->forge->addForeignKey('parent_id', 'categories', 'id', 'SET NULL', 'CASCADE', 'categories_parent_id_foreign');
        $this->forge->processIndexes('categories');
    }

    public function down()
    {
        $this->forge->dropForeignKey('categories', 'categories_parent_id_foreign');
        $this->forge->dropColumn('categories', 'parent_id');
    }
}
