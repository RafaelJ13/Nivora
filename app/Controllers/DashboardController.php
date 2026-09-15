<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use App\Models\CategoryModel;
use App\Models\TransactionModel;

class DashboardController extends BaseController
{
    protected AccountModel $accountModel;
    protected TransactionModel $transactionsModel;
    protected CategoryModel $categoriesModel;

    public function __construct()
    {
        $this->accountModel = model(AccountModel::class);
        $this->categoriesModel = model(CategoryModel::class);
        $this->transactionsModel = model(TransactionModel::class);
    }

    public function index()
    {

        if (!function_exists('auth') || !auth()->loggedIn()) {
            return redirect()
                ->to(site_url('login'));
        }

        $userId = auth()->user()->id;


        $categories = $this->categoriesModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        $accounts = $this->accountModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        $transactions = $this->transactionsModel
            ->select('transactions.*, accounts.name AS account_name, categories.name AS category_name')
            ->join('accounts', 'accounts.id = transactions.account_id')
            ->join('categories', 'categories.id = transactions.category_id')
            ->where('transactions.user_id', $userId)
            ->orderBy('transactions.transaction_date', 'DESC')
            ->findAll();

        
        $totalBalance = 0;
        $accountBalances = [];

        foreach ($accounts as $account) {
            $accountBalances[$account->id] = (int) $account->initial_balance;
            $account->current_balance = (int) $account->initial_balance;
            $totalBalance += $accountBalances[$account->id];
        }

        $totalIncome = 0;
        $totalExpenses = 0;
        $expensesByCategory = [];
        $currentMonth = date('Y-m');

        foreach ($transactions as $transaction) {
            $amount = (int) $transaction->amount;
            $type = strtoupper((string) $transaction->type);

            if ($type === 'INCOME') {
                $totalBalance += $amount;
                $accountBalances[$transaction->account_id] = ($accountBalances[$transaction->account_id] ?? 0) + $amount;
            } elseif ($type === 'EXPENSE') {
                $totalBalance -= $amount;
                $accountBalances[$transaction->account_id] = ($accountBalances[$transaction->account_id] ?? 0) - $amount;
            }

            if (isset($accountBalances[$transaction->account_id])) {
                foreach ($accounts as $account) {
                    if ((int) $account->id === (int) $transaction->account_id) {
                        $account->current_balance = $accountBalances[$transaction->account_id];
                        break;
                    }
                }
            }

            $transactionMonth = substr((string) $transaction->transaction_date, 0, 7);

            if ($transactionMonth === $currentMonth) {
                if ($type === 'INCOME') {
                    $totalIncome += $amount;
                } elseif ($type === 'EXPENSE') {
                    $totalExpenses += $amount;

                    $categoryName = $transaction->category_name ?? 'Sem categoria';

                    if (! isset($expensesByCategory[$categoryName])) {
                        $expensesByCategory[$categoryName] = 0;
                    }

                    $expensesByCategory[$categoryName] += $amount;
                }
            }
        }
        return view('dashboard', [
            'accounts'          => $accounts,
            'categories'        => $categories,
            'transactions'      => $transactions,
            'totalIncome'       => $totalIncome,
            'totalExpenses'     => $totalExpenses,
            'totalBalance'      => $totalBalance,
            'expensesByCategory' => $expensesByCategory,
        ]);
    }
}
