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

    public function create() 
    {
        return view('accounts/create');
    }

    public function edit(int $id)
    {
        $authUserId = auth()->user()->id;

        $account = $this->model
            ->where('user_id', $authUserId)
            ->find($id);

        if (! $account) return redirect()->to(site_url('accounts'))->with('error', 'Conta não encontrada.');


        return view('accounts/edit', [
            'account' => $account,
        ]);
    }

    public function show(int $id) 
    {
        $authUserId = auth()->user()->id;

        $account = $this->model
            ->where('user_id', $authUserId)
            ->find($id);

        if (! $account) {
            return redirect()
                ->to(site_url('accounts'))
                ->with('error', 'Conta não encontrada.');
        }

        return view('accounts/show', [
            'account' => $account
        ]);
    }
    
    public function post()
    {
        $formData = $this->request->getPost();

        $initialBalance = $formData['initial_balance'] ?? 0;
        $formData['initial_balance'] = (int) round((float) $initialBalance * 100);

        $formData['user_id'] = auth()->user()->id;

        if ($this->model->insert($formData) === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->model->errors());
        }

        return redirect()
            ->to(site_url('accounts'))
            ->with('success', 'Conta criada com sucesso!');
    }

    public function put(int $id)
    {
        $authUserId = auth()->user()->id;

        $account = $this->model
            ->where('user_id', $authUserId)
            ->find($id);

        if (! $account) {
            return redirect()
                ->to(site_url('accounts'))
                ->with('error', 'Conta não encontrada.');
        }

        $formData = $this->request->getPost();

        $initialBalance = $formData['initial_balance'] ?? 0;
        $formData['initial_balance'] = (int) round((float) $initialBalance * 100);

        if ($this->model->update($id, $formData) === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->model->errors());
        }

        return redirect()
            ->to(site_url('accounts'))
            ->with('success', 'Conta atualizada com sucesso!');
    }

    public function delete(int $id) 
    {
        $authUserId = auth()->user()->id;

        $account = $this->model
            ->where('user_id', $authUserId)
            ->find($id);

        if (! $account) {
            return redirect()
                ->to(site_url('accounts'))
                ->with('error', 'Conta não encontrada.');
        }
        
        if ($this->model->delete($id) === false) {
            return redirect()
                ->back()
                ->with('error', 'Não foi possível eliminar a conta.');
        }

        return redirect()
            ->to(site_url('accounts'))
            ->with('success', 'Conta eliminada com sucesso!');
    }
}
