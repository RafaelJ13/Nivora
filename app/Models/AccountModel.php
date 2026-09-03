<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table            = 'accounts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'user_id',
        'name',
        'type',
        'initial_balance',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'user_id'         => 'required|is_natural_no_zero',
        'name'            => 'required|min_length[2]|max_length[100]',
        'type'            => 'required|max_length[20]',
        'initial_balance' => 'required|integer',
    ];
}