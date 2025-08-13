<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRatingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'int',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type' => 'int',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'star' => [
                'type' => 'int',
                'constraint' => 1,
                'default' => 1
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'cascade', 'cascade');
        $this->forge->createTable('ratings');
    }

    public function down()
    {
        $this->forge->dropTable('ratings');
    }
}
