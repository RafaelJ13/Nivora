<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use App\Models\CategoryModel;
use App\Models\TransferModel;
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
            ->paginate(5);

        $recentTransactions = $this->transactionsModel
            ->select('transactions.*, accounts.name AS account_name, categories.name AS category_name')
            ->join('accounts', 'accounts.id = transactions.account_id')
            ->join('categories', 'categories.id = transactions.category_id')
            ->where('transactions.user_id', $userId)
            ->orderBy('transactions.transaction_date', 'DESC')
            ->findAll(6);

        $recentTransfers = model(TransferModel::class)
            ->select('transfers.*, from_acc.name AS account_from_name, to_acc.name AS account_to_name')
            ->join('accounts from_acc', 'from_acc.id = transfers.account_from_id')
            ->join('accounts to_acc', 'to_acc.id = transfers.account_to_id')
            ->where('transfers.user_id', $userId)
            ->orderBy('transfers.transfer_date', 'DESC')
            ->asObject()
            ->findAll(6);

        foreach ($recentTransfers as $transfer) {
            $transfer->type = 'TRANSFER';
            $transfer->row_type = 'TRANSFER';
        }

        $recentMovements = array_merge($recentTransactions, $recentTransfers);
        usort($recentMovements, static function ($a, $b): int {
            $dateA = $a->transaction_date ?? $a->transfer_date ?? '';
            $dateB = $b->transaction_date ?? $b->transfer_date ?? '';
            return strcmp((string) $dateB, (string) $dateA);
        });
        $recentMovements = array_slice($recentMovements, 0, 6);

        $currentWeekStart = new \DateTimeImmutable('monday this week');
        $cashFlowStart = $currentWeekStart->modify('-4 weeks')->setTime(0, 0);
        $cashFlowEnd = new \DateTimeImmutable('tomorrow');
        $cashFlowWeeks = [];
        for ($week = 0; $week < 5; $week++) {
            $weekStart = $cashFlowStart->modify("+{$week} weeks");
            $weekEnd = $weekStart->modify('+6 days');
            $cashFlowWeeks[$weekStart->format('Y-m-d')] = [
                'label' => $weekStart->format('d/m') . '–' . $weekEnd->format('d/m'),
                'income' => 0,
                'expenses' => 0,
            ];
        }

        $cashFlowTransactions = $this->transactionsModel
            ->select('type, amount, transaction_date')
            ->where('user_id', $userId)
            ->where('transaction_date >=', $cashFlowStart->format('Y-m-d H:i:s'))
            ->where('transaction_date <', $cashFlowEnd->format('Y-m-d H:i:s'))
            ->findAll();

        foreach ($cashFlowTransactions as $transaction) {
            $type = strtoupper((string) $transaction->type);
            if (! in_array($type, ['INCOME', 'EXPENSE'], true)) {
                continue;
            }

            $weekStart = (new \DateTimeImmutable((string) $transaction->transaction_date))
                ->modify('monday this week')
                ->setTime(0, 0)
                ->format('Y-m-d');
            $key = $type === 'INCOME' ? 'income' : 'expenses';
            $cashFlowWeeks[$weekStart][$key] += (int) $transaction->amount;
        }


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
        $previousMonthBalance = $totalBalance - $totalIncome + $totalExpenses;
        $balanceChangePercent = $previousMonthBalance !== 0
            ? round((($totalBalance - $previousMonthBalance) / abs($previousMonthBalance)) * 100, 1)
            : null;

        return view('dashboard', [
            'accounts'          => $accounts,
            'categories'        => $categories,
            'transactions'      => $transactions,
            'recentMovements'   => $recentMovements,
            'cashFlowWeeks'     => array_values($cashFlowWeeks),
            'totalIncome'       => $totalIncome,
            'totalExpenses'     => $totalExpenses,
            'totalBalance'      => $totalBalance,
            'balanceChangePercent' => $balanceChangePercent,
            'expensesByCategory' => $expensesByCategory,
        ]);
    }
}
