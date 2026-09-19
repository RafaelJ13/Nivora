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

        $type       = $this->request->getGet('type');
        $accountId  = $this->request->getGet('account_id');
        $categoryId = $this->request->getGet('category_id');
        $search     = $this->request->getGet('search');
        $sort       = $this->request->getGet('sort') ?? 'transactions.transaction_date';
        $direction  = $this->request->getGet('direction') ?? 'DESC';

        $allowedSorts = [
            'transactions.description',
            'accounts.name',
            'categories.name',
            'transactions.transaction_date',
            'transactions.amount'
        ];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'transactions.transaction_date';
        }
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';

        $builder  = $this->model
            ->select('transactions.*, accounts.name AS account_name, categories.name AS category_name')
            ->join('accounts', 'accounts.id = transactions.account_id')
            ->join('categories', 'categories.id = transactions.category_id')
            ->where('transactions.user_id', $authUserId);

        if (!empty($type)) {
        $builder->where('transactions.type', $type);
        }

        if (!empty($accountId)) {
            $builder->where('transactions.account_id', $accountId);
        }

        if (!empty($categoryId)) {
            $builder->where('transactions.category_id', $categoryId);
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('transactions.description', $search)
                ->orLike('accounts.name', $search)
                ->orLike('categories.name', $search)
                ->groupEnd();
        }
        if ($sort === 'transactions.amount') {
            $builder->orderBy("CASE WHEN transactions.type = 'expense' THEN -transactions.amount ELSE transactions.amount END", $direction);
        } else {
            $builder->orderBy($sort, $direction);
        }

        $transaction = $builder->orderBy($sort , $direction )->paginate(5);

        $accountModel   = model('AccountModel');
        $categoryModel = model('CategoryModel');

        return view('transactions/index', [
            'transactions' => $transaction,
            'pager'        => $this->model->pager,
            'accounts'     => $accountModel->where('user_id', $authUserId)->findAll(),
            'categories'   => $categoryModel->where('user_id', $authUserId)->findAll(),
        ]);
    }

    public function New() {
        return view('transactions/create', $this->formData());
    }

    public function show(int $id) {
       $authUserId = auth()->user()->id;

        $transaction = $this->model
            ->select('transactions.*, accounts.name AS account_name, categories.name AS category_name')
            ->join('accounts', 'accounts.id = transactions.account_id')
            ->join('categories', 'categories.id = transactions.category_id')
            ->where('transactions.user_id', $authUserId)
            ->find($id);

        if (! $transaction) return redirect()->to(site_url('transactions'))->with('error', 'Transação não encontrada');

        return view('transactions/show', $this->formData() + [
            'transaction' => $transaction,
        ]);
    }

    public function edit(int $id) {
        $authUserId = auth()->user()->id;

        $transaction = $this->model
            ->select('transactions.*, accounts.name AS account_name, categories.name AS category_name')
            ->join('accounts', 'accounts.id = transactions.account_id')
            ->join('categories', 'categories.id = transactions.category_id')
            ->where('transactions.user_id', $authUserId)
            ->find($id);

        if (! $transaction) return redirect()->to(site_url('transactions'))->with('error', 'Transação não encontrada');

        return view('transactions/edit', $this->formData() + [
            'transaction' => $transaction,
        ]);
    }

    public function post() {
        $authUserId = auth()->user()->id;

        $formData = $this->request->getPost();
        $amount = $formData['amount'] ?? 0;
        $formData['amount'] = (int) round((float) str_replace(',', '.', $amount) * 100);
        $formData['user_id'] = $authUserId;
        $formData['type'] = strtoupper((string) ($formData['type'] ?? ''));

        $referenceError = $this->validateReferences($authUserId, $formData);
        if ($referenceError !== null) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $referenceError);
        }

        $date = \DateTime::createFromFormat('!Y-m-d\TH:i', (string) ($formData['transaction_date'] ?? ''));
        if (! $date || $date->format('Y-m-d\TH:i') !== ($formData['transaction_date'] ?? '')) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', ['transaction_date' => 'Indica uma data válida.']);
        }
        $formData['transaction_date'] = $date->format('Y-m-d H:i:s');

        if($this->model->insert($formData) === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->model->errors());
        }

        return redirect()
            ->to(site_url('transactions'))
            ->with('success', 'Transação criada com sucesso!');

    }

    public function put(int $id) {
        $authUserId = auth()->user()->id;

        $transaction = $this->model
            ->where('user_id', $authUserId)
            ->find($id);

        if (! $transaction) {
            return redirect()
                ->to(site_url('transactions'))
                ->with('error', 'Conta não encontrada.');
        }

        $formData = $this->request->getPost();
        $amount = $formData['amount'] ?? 0;
        $formData['amount'] = (int) round((float) str_replace(',', '.', $amount) * 100);
        $formData['user_id'] = $authUserId;
        $formData['type'] = strtoupper((string) ($formData['type'] ?? ''));

        $referenceError = $this->validateReferences($authUserId, $formData);
        if ($referenceError !== null) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $referenceError);
        }

        $date = \DateTime::createFromFormat('!Y-m-d\TH:i', (string) ($formData['transaction_date'] ?? ''));
        if (! $date || $date->format('Y-m-d\TH:i') !== ($formData['transaction_date'] ?? '')) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', ['transaction_date' => 'Indica uma data válida.']);
        }
        $formData['transaction_date'] = $date->format('Y-m-d H:i:s');

        if($this->model->update($id, $formData) === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->model->errors());
        }

        return redirect()
            ->to(site_url('transactions'))
            ->with('success', 'Transação criada com sucesso!');
    }

    public function delete(int $id) {
        $authUserId = auth()->user()->id;

        $transaction = $this->model
            ->where('user_id', $authUserId)
            ->find($id);

        if (! $transaction) {
            return redirect()
                ->to(site_url('transactions'))
                ->with('error', 'transação não encontrada.');
        }
        
        if ($this->model->delete($id) === false) {
            return redirect()
                ->back()
                ->with('error', 'Não foi possível eliminar a transação.');
        }

        return redirect()
            ->to(site_url('transactions'))
            ->with('success', 'transação eliminada com sucesso!');
    }

    private function formData(): array
    {
        $userId = auth()->user()->id;

        return [
            'accounts' => $this->accountModel->where('user_id', $userId)->orderBy('name')->findAll(),
            'categories' => $this->categoryModel->where('user_id', $userId)->orderBy('name')->findAll(),
        ];
    }

    private function validateReferences(int $userId, array $formData): ?string
    {
        $accountId = (int) ($formData['account_id'] ?? 0);
        $categoryId = (int) ($formData['category_id'] ?? 0);
        $type = strtoupper((string) ($formData['type'] ?? ''));

        $account = $this->accountModel
            ->where('user_id', $userId)
            ->find($accountId);

        if (! $account) {
            return 'A conta selecionada não é válida.';
        }

        $category = $this->categoryModel
            ->where('user_id', $userId)
            ->find($categoryId);

        if (! $category) {
            return 'A categoria selecionada não é válida.';
        }

        if (in_array($type, ['INCOME', 'EXPENSE'], true) && strtoupper((string) $category->type) !== $type) {
            return 'A categoria selecionada não corresponde ao tipo da transação.';
        }

        return null;
    }
}
