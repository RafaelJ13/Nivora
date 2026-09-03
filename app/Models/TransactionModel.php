<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'user_id',
        'account_id',
        'category_id',
        'type',
        'amount',
        'description',
        'transaction_date',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'user_id'          => 'required|is_natural_no_zero',
        'account_id'       => 'required|is_natural_no_zero',
        'category_id'      => 'required|is_natural_no_zero',
        'type'             => 'required|in_list[INCOME,EXPENSE]',
        'amount'           => 'required|integer', // Garantia de cêntimos
        'transaction_date' => 'required|valid_date[Y-m-d H:i:s,Y-m-d]',
    ];
}