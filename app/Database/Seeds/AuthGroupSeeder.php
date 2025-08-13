<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthGroupSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'admin',
                'description' => 'Administrator Site'
            ],
            [
                'name' => 'user',
                'description' => 'Regular User'
            ]
        ];

        $this->db->table('auth_groups')->insertBatch($data);
    }
}
