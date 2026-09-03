<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Instancia o UserModel oficial do Shield via helper nativo do CI4
        $users = model('CodeIgniter\Shield\Models\UserModel');

        // Evita criar duplicados se o seeder for executado novamente
        $existingUser = $users->findByCredentials(['email' => 'teste@nivora.pt']);

        if (! $existingUser) {
            $user = new User([
                'username' => 'utilizadorteste',
                'name'     => 'Utilizador Teste',
                'email'    => 'teste@nivora.pt',
                'password' => '12345678', // O Shield encripta e trata da auth_identities
            ]);

            // Guarda o utilizador
            $users->save($user);

            // Resgata a entidade criada e ativa a conta
            $user = $users->findById($users->getInsertID());
            $user->activate();
        }
    }
}