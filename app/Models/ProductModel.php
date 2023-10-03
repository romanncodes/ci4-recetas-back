<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'productos';
    protected $primaryKey = 'pr_id';

    //protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    //protected $useSoftDeletes = false;

    protected $allowedFields = [
        'pr_id', 
        'pr_name', 
        'pr_unity',
        'pr_price',
        'pr_img'
    ];

    /*protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;*/
}