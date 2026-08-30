<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Despesas (EXPENSE)
            ['user_id' => 1, 'name' => 'Alimentação', 'type' => 'EXPENSE', 'created_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'name' => 'Transportes',  'type' => 'EXPENSE', 'created_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'name' => 'Habitação',    'type' => 'EXPENSE', 'created_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'name' => 'Lazer',        'type' => 'EXPENSE', 'created_at' => date('Y-m-d H:i:s')],
            
            // Receitas (INCOME)
            ['user_id' => 1, 'name' => 'Salário',      'type' => 'INCOME',  'created_at' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'name' => 'Investimentos','type' => 'INCOME',  'created_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}