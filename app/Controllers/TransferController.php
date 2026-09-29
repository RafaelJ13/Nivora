<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransferModel;
use App\Models\AccountModel;

class TransferController extends BaseController
{
    protected TransferModel $model;
    protected AccountModel $accountModel;

    public function __construct()
    {
        $this->model = model(TransferModel::class);
        $this->accountModel = model(AccountModel::class);
    }

    public function index()
    {
        //
    }


    public function show(int $id) {
        $userId = auth()->user()->id;
        $transfer = $this->model
            ->select('transfers.*, from_acc.name AS account_from_name, to_acc.name AS account_to_name')
            ->join('accounts from_acc', 'from_acc.id = transfers.account_from_id')
            ->join('accounts to_acc', 'to_acc.id = transfers.account_to_id')
            ->where('transfers.user_id', $userId)
            ->asObject()
            ->find($id);
        if (! $transfer) {
            return redirect()->to(site_url('transactions'))->with('error', 'Transferência não encontrada.');
        }
        return view('transfer/show', [
            'transfer' => $transfer,
        ]);
    }


    public function new() {

        $userId = auth()->user()->id;


        $accountModel = model('AccountModel');
        $accounts = $accountModel->where('user_id', $userId)->findAll();

        $data = [
            'accounts' => $accounts
        ];


        return view('transfer/create', $data);
    }
   

    public function post() {
        $authUserId = auth()->user()->id;
        $formData = $this->request->getPost();
        $amount = $formData['amount'] ?? 0;
        $formData['amount'] = (int) round((float) str_replace(',', '.', $amount));

        $formData['user_id'] = $authUserId;


        
        $referenceError = $this->validateReferences($authUserId, $formData);
        if ($referenceError !== null) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $referenceError);
        }

        $date = \DateTime::createFromFormat('!Y-m-d\TH:i', (string) ($formData['transfer_date'] ?? ''));
        if (! $date || $date->format('Y-m-d\TH:i') !== ($formData['transfer_date'] ?? '')) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', ['transfer_date' => 'Indica uma data válida.']);
        }
        $formData['transfer_date'] = $date->format('Y-m-d H:i:s');

        if($this->model->insert($formData) === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->model->errors());
        }

        return redirect()
            ->to(site_url('transactions'))
            ->with('success', 'Transferencia criada com sucesso!');

    }

    private function validateReferences(int $userId, array $formData): ?string
    {
        $fromId = (int) ($formData['account_from_id'] ?? 0);
        $toId   = (int) ($formData['account_to_id'] ?? 0);

        if ($fromId === $toId) {
            return 'A conta de origem e destino não podem ser iguais.';
        }

        $accountModel = model(AccountModel::class);

        $fromAccount = $accountModel->where('user_id', $userId)->find($fromId);
        if (! $fromAccount) {
            return 'A conta de origem selecionada não é válida.';
        }

        $toAccount = $accountModel->where('user_id', $userId)->find($toId);
        if (! $toAccount) {
            return 'A conta de destino selecionada não é válida.';
        }

        return null;
    }
}
