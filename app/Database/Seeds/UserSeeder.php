<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'       => 'Utilizador Teste',
            'email'      => 'teste@nivora.pt',
            'password'   => password_hash('12345678', PASSWORD_BCRYPT),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('users')->insert($data);
    }
}