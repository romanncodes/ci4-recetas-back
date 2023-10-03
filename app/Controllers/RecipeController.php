<?php

namespace App\Controllers;

use App\Models\RecipesModel;
use CodeIgniter\API\ResponseTrait;

class RecipeController extends Auth{
    protected $recipeModel;
    use ResponseTrait;

    public function __construct(){
        $this->recipeModel = new RecipesModel();
    }

    public function recipeList(){  
        $this->headersConfig();
        $id_product = $this->request->getVar('id-product');
        $db      = \Config\Database::connect();
        $builder = $db->table('recetas r');
        $builder->select('r.re_id, r.re_product, r.re_supply, r.re_quantity, i.in_name, i.in_unity, i.in_quantity, i.in_price');
        $builder->join('insumos i', "r.re_supply = i.in_id");
        $builder->join('productos p', "r.re_product = p.pr_id");
        $builder->where('p.pr_id', $id_product);

        $query = $builder->get();
        echo json_encode($query->getResult());

        
    }

   

    public function saveRecipe(){
        $this->headersConfig();
        $product  = $this->request->getPost("product");
        $supply   = $this->request->getPost('supply');
        $quantity = $this->request->getPost('quantity');
        
        $data = [
            're_product'    => $product,
            're_supply'     => $supply,
            're_quantity'   => $quantity
        ];

        $this->recipeModel->insert($data);
        return $this->respond(['status' => 200], 200);
    }

    public function deleteRecipe(){
        $this->headersConfig();
        $id  = $this->request->getPost("id");
        $this->recipeModel->delete(["re_id"=>$id]);
        return $this->respond(['status' => 200], 200);
    }    
}
