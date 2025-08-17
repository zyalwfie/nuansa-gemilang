<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'constraint' => 11,
                'auto_increment' => true,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'int',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'address_id' => [
                'type' => 'int',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'status' => [
                'type' => 'enum',
                'constraint' => ['tertunda', 'berhasil', 'gagal'],
                'default' => 'tertunda',
            ],
            'total_price' => [
                'type' => 'int',
                'constraint' => 11,
            ],
            'notes' => [
                'type' => 'text',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'cascade', 'cascade');
        $this->forge->addForeignKey('address_id', 'addresses', 'id', 'cascade', 'cascade');
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
