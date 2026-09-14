<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AccountsController extends BaseController
{

    // 2. Obter o AccountModel
    protected $model;

    public function __construct()
    {
        $this->model = model(\App\Models\AccountModel::class);
    }

    public function index()
    {
        $authuserId = auth()->user()->id;


        // 3. Procurar apenas as contas desse utilizador
        $accounts = $this->model
            ->where('user_id', $authuserId)
            ->findAll();

        // 4. Enviar as contas para a view
        return view('accounts/index', [
            'accounts' => $accounts,
        ]);
    }

    public function create() {
        return view('accounts/create');
    }

    public function post() 
    {
        $formData = $this->request->getPost();

        $formData['user_id'] = auth()->user()->id;

        if($this->model->insert($formData) === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this_>model->errors());
        };

        return redirect()
            ->to(site_url('accounts'))
            ->with('success', 'Conta criada com sucesso!');
    }
}
