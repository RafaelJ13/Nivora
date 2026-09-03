<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'user_id',
        'name',
        'type',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validação restrita ao ENUM do MySQL
    protected $validationRules = [
        'user_id' => 'required|is_natural_no_zero',
        'name'    => 'required|min_length[2]|max_length[100]',
        'type'    => 'required|in_list[INCOME,EXPENSE]',
    ];
}