<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id'          => 1,
                'account_id'       => 1,
                'category_id'      => 5, // Categoria Salário
                'type'             => 'INCOME',
                'amount'           => 120000, // 1.200,00 €
                'description'      => 'Vencimento Mensal',
                'transaction_date' => date('Y-m-01 10:00:00'),
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'          => 1,
                'account_id'       => 1,
                'category_id'      => 1, // Categoria Alimentação
                'type'             => 'EXPENSE',
                'amount'           => 4550, // 45,50 €
                'description'      => 'Compras Continente',
                'transaction_date' => date('Y-m-15 18:30:00'),
                'created_at'       => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('transactions')->insertBatch($data);
    }
}