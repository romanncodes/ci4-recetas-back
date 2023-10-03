<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'us_id';

    //protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    //protected $useSoftDeletes = false;

    protected $allowedFields = [
        'us_id', 
        'us_name', 
        'us_password',
        'us_email',
    ];

    /*protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;*/
}