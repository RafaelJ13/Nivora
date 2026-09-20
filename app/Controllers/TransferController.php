<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransferModel;
use App\Models\AccountModel;

class TransferController extends BaseController
{
    protected TransferModel $model;

    public function __construct()
    {
        $this->model = model(TransferModel::class);
    }

    public function index()
    {
        //
    }
    public function new() {
        // Obter o ID do utilizador autenticado (ajusta conforme a tua lógica de sessão)
        $userId = auth()->user()->id;

        // Carregar o model das contas para ir buscar apenas as contas deste utilizador
        $accountModel = model('AccountModel');
        $accounts = $accountModel->where('user_id', $userId)->findAll();

        $data = [
            'accounts' => $accounts
        ];

        // Carregar a view que criámos anteriormente
        return view('transfer/create', $data);
    }
   
}
