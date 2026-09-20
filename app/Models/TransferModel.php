<?php

namespace App\Models;

use CodeIgniter\Model;

class TransferModel extends Model
{
    protected $table            = 'transfers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'account_from_id',
        'account_to_id',
        'amount',
        'transfer_date'

    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = null;
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id'          => 'required|is_natural_no_zero',
        'account_from_id'  => 'required|is_natural_no_zero',
        'account_to_id'    => 'required|is_natural_no_zero|diff[account_from_id]',
        'amount'           => 'required|integer',
        'transfer_date'    => 'required|valid_date[Y-m-d H:i:s]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

}
