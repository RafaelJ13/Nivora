<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    protected $accountModel;

    public function __construct()
    {
        $this->accountModel = model(\App\Models\AccountModel::class);
    }

    public function index()
    {

        if (!function_exists('auth') || !auth()->loggedIn()) {
            return redirect()
                ->to(site_url('login'));
        }

        $userId = auth()->user()->id;


        $accounts = $this->accountModel
            ->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('dashboard', [
            'accounts'          => $accounts,
        ]);
    }
}
