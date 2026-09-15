<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use CodeIgniter\HTTP\ResponseInterface;

class AccountsController extends BaseController
{

    // 2. Obter o AccountModel
    protected $model;
    protected TransactionModel $transactionsModel;

    public function __construct()
    {
        $this->model = model(\App\Models\AccountModel::class);
        $this->transactionsModel = model(TransactionModel::class);
    }

    public function index()
    {
        $authuserId = auth()->user()->id;


        // 3. Procurar apenas as contas desse utilizador
        $accounts = $this->model
            ->where('user_id', $authuserId)
            ->findAll();

        $transactions = $this->transactionsModel
            ->select('account_id, type, amount')
            ->where('user_id', $authuserId)
            ->findAll();

        foreach ($accounts as $account) {
            $account->current_balance = (int) $account->initial_balance;
        }

        foreach ($transactions as $transaction) {
            foreach ($accounts as $account) {
                if ((int) $account->id !== (int) $transaction->account_id) {
                    continue;
                }

                if (strtoupper((string) $transaction->type) === 'INCOME') {
                    $account->current_balance += (int) $transaction->amount;
                } elseif (strtoupper((string) $transaction->type) === 'EXPENSE') {
                    $account->current_balance -= (int) $transaction->amount;
                }

                break;
            }
        }

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

        $transactions = $this->transactionsModel
            ->select('transactions.*, categories.name AS category_name')
            ->join('categories', 'categories.id = transactions.category_id', 'left')
            ->where('transactions.user_id', $authUserId)
            ->where('transactions.account_id', $id)
            ->orderBy('transactions.transaction_date', 'DESC')
            ->findAll();

        return view('accounts/show', [
            'account' => $account,
            'transactions' => $transactions,
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
                ->with('error', $this->model->errors());
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
                ->with('error', $this->model->errors());
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
