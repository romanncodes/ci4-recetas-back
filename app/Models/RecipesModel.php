<?php

namespace App\Models;

use CodeIgniter\Model;

class RecipesModel extends Model
{
    protected $table      = 'recetas';
    protected $primaryKey = 're_id';

    //protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    //protected $useSoftDeletes = false;

    protected $allowedFields = [
        're_id', 
        're_product', 
        're_supply',
        're_quantity'
    ];

    /*protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;*/
}