<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplyModel extends Model
{
    protected $table      = 'insumos';
    protected $primaryKey = 'in_id';

    //protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    //protected $useSoftDeletes = false;

    protected $allowedFields = [
        'in_id', 
        'in_name', 
        'in_unity',
        'in_quantity',
        'in_price',
    ];

    /*protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;*/
}