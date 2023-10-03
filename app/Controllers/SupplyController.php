<?php

namespace App\Controllers;

use App\Models\SupplyModel;
use CodeIgniter\API\ResponseTrait;

class SupplyController extends Auth{
    protected $supplyModel;
    use ResponseTrait;

    public function __construct(){
        $this->supplyModel = new SupplyModel();
    }

    public function supplyList(){  
        $page = $this->request->getVar('page')??1;
        $size = $this->request->getVar('size')??3;

        $supplies = $this->supplyModel->paginate($size);
        $totalElements = $this->supplyModel->countAllResults();

        $number     = ($page <= 0)?null:$page;
        $totalPages = ($size <= 0)?null: ceil($totalElements/$size);
        $firstPage  = $number==="1";
        $lastPage   = $number===strval($totalPages);

        $this->headersConfig();
        return $this->respond(
            [
                'data'=>$supplies,
                'pagination'=>[
                    'page'=>$page,
                    'size'=>$size,
                    'totalElements'=>$totalElements,
                    'totalPages'=>$totalPages,
                    'number'=>$number,
                    'firstPage'=>$firstPage,
                    'lastPage'=>$lastPage
                ]
            ]
            , 200);
        
    }

    public function supplyListByName(){  
        $name = $this->request->getVar('name');
        $page = $this->request->getVar('page')??1;
        $size = $this->request->getVar('size')??3;

        $supplies = $this->supplyModel->like('in_name', $name,'both')->paginate($size);
        $totalElements = $this->supplyModel->like('in_name', $name,'both')->countAllResults(false);

        $number     = ($page <= 0)?null:$page;
        $totalPages = ($size <= 0)?null: ceil($totalElements/$size);
        $firstPage  = $number==="1";
        $lastPage   = $number===strval($totalPages);

        $this->headersConfig();
        return $this->respond(
            [
                'data'=>$supplies,
                'pagination'=>[
                    'page'=>$page,
                    'size'=>$size,
                    'totalElements'=>$totalElements,
                    'totalPages'=>$totalPages,
                    'number'=>$number,
                    'firstPage'=>$firstPage,
                    'lastPage'=>$lastPage
                ]
            ]
            , 200);
        
    }

    public function saveSupply(){
        $this->headersConfig();
        $data = [
            "in_name" => $this->request->getPost('name'),
            "in_quantity" => $this->request->getPost('quantity'),
            "in_unity" => $this->request->getPost('unity'),
            "in_price" => $this->request->getPost('price')
        ];
        $this->supplyModel->insert($data);
        return $this->respond(['status' => 200], 200);
    }

    public function editSupply(){
        $this->headersConfig();
        $id = $this->request->getPost('id');
        $data = [
            "in_name" => $this->request->getPost('name'),
            "in_quantity" => $this->request->getPost('quantity'),
            "in_unity" => $this->request->getPost('unity'),
            "in_price" => $this->request->getPost('price')
        ];
        $this->supplyModel->update($id, $data);
        return $this->respond(['status' => 200], 200);
    }
    
}
