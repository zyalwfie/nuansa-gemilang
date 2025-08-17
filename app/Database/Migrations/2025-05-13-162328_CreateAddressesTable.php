<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAddressTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                 => [
                'type'           => 'int',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id'            => [
                'type'           => 'int',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'label'              => [
                'type'           => 'varchar',
                'constraint'     => 20,
            ],
            'phone_number'       => [
                'type'           => 'varchar',
                'constraint'     => 15,
            ],
            'street_address'     => [
                'type'           => 'varchar',
                'constraint'     => 255
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'cascade', 'cascade');
        $this->forge->createTable('addresses');
    }

    public function down()
    {
        $this->forge->dropTable('addresses');
    }
}
