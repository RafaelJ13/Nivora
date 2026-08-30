<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id'         => 1,
                'name'            => 'Conta Corrente CGD',
                'type'            => 'bank',
                'initial_balance' => 100000, // 1.000,00 €
                'created_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'         => 1,
                'name'            => 'Carteira / Dinheiro',
                'type'            => 'cash',
                'initial_balance' => 5000,   // 50,00 €
                'created_at'      => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('accounts')->insertBatch($data);
    }
}