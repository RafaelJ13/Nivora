<?php

namespace App\Controllers;

use CodeIgniter\Shield\Validation\ValidationRules;

class RegisterController extends \CodeIgniter\Shield\Controllers\RegisterController
{
    public function registerAction(): \CodeIgniter\HTTP\RedirectResponse
    {
        $post = $this->request->getPost();
        $post['username'] = $this->generateUsername((string) ($post['email'] ?? ''));
        $this->request->setGlobal('post', $post);

        return parent::registerAction();
    }

    protected function getValidationRules(): array
    {
        $rules = (new ValidationRules())->getRegistrationRules();

        $rules['name'] = [
            'label' => 'Nome completo',
            'rules' => [
                'required',
                'min_length[2]',
                'max_length[100]',
            ],
        ];

        return $rules;
    }

    private function generateUsername(string $email): string
    {
        $username = strtolower((string) strstr($email, '@', true));
        $username = preg_replace('/[^a-z0-9.]+/', '.', $username) ?: 'user';
        $username = trim(preg_replace('/\.+/', '.', $username), '.');
        $username = substr($username ?: 'user', 0, 30);

        $baseUsername = $username;
        $suffix = 1;
        $users = model(\App\Models\UserModel::class)->withDeleted();

        while ($users->where('username', $username)->first() !== null) {
            $suffixText = (string) $suffix++;
            $username = substr($baseUsername, 0, 30 - strlen($suffixText) - 1) . '.' . $suffixText;
        }

        return $username;
    }
}
