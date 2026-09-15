<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use App\Models\CategoryModel;
use App\Models\TransactionModel;

class TransactionController extends BaseController
{
    protected TransactionModel $model;
    protected AccountModel $accountModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->model = model(TransactionModel::class);
        $this->accountModel = model(AccountModel::class);
        $this->categoryModel = model(CategoryModel::class);
    }

    public function index()
    {
        $authUserId = auth()->user()->id;

        $transaction  = $this->model
            ->select('transactions.*, accounts.name AS account_name, categories.name AS category_name')
            ->join('accounts', 'accounts.id = transactions.account_id')
            ->join('categories', 'categories.id = transactions.category_id')
            ->where('transactions.user_id', $authUserId)
            ->findAll();

        return view('transactions/index', [
            'transactions' => $transaction,
        ]);
    }

    public function New() {
        return view('transactions/create', $this->formData());
    }

    public function post() {
        $authUserId = auth()->user()->id;

        $formData = $this->request->getPost();
        $amount = $formData['amount'] ?? 0;
        $formData['amount'] = (int) round((float) str_replace(',', '.', $amount) * 100);
        $formData['user_id'] = $authUserId;
        $formData['type'] = strtoupper((string) ($formData['type'] ?? ''));

        $date = \DateTime::createFromFormat('!Y-m-d\TH:i', (string) ($formData['transaction_date'] ?? ''));
        if (! $date || $date->format('Y-m-d\TH:i') !== ($formData['transaction_date'] ?? '')) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', ['transaction_date' => 'Indica uma data válida.']);
        }
        $formData['transaction_date'] = $date->format('Y-m-d H:i:s');

        if($this->model->insert($formData) === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->model->errors());
        }

        return redirect()
            ->to(site_url('transactions'))
            ->with('success', 'Transação criada com sucesso!');

    }

    private function formData(): array
    {
        $userId = auth()->user()->id;

        return [
            'accounts' => $this->accountModel->where('user_id', $userId)->orderBy('name')->findAll(),
            'categories' => $this->categoryModel->where('user_id', $userId)->orderBy('name')->findAll(),
        ];
    }
}
