<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\API\ResponseTrait;

class ProductController extends Auth{
    protected $productModel;
    use ResponseTrait;

    public function __construct(){
        $this->productModel = new ProductModel();
    }

    public function productList(){  
        $page = $this->request->getVar('page')??1;
        $size = $this->request->getVar('size')??6;

        $products = $this->productModel->paginate($size);
        $totalElements = $this->productModel->countAllResults();

        $number     = ($page <= 0)?null:$page;
        $totalPages = ($size <= 0)?null: ceil($totalElements/$size);
        $firstPage  = $number==="1";
        $lastPage   = $number===strval($totalPages);

        $this->headersConfig();
        return $this->respond(
            [
                'data'=>$products,
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

    public function productListByName(){  
        $name = $this->request->getVar('name');
        $page = $this->request->getVar('page')??1;
        $size = $this->request->getVar('size')??6;

        $products = $this->productModel->like('pr_name', $name,'both')->paginate($size);
        $totalElements = $this->productModel->like('pr_name', $name,'both')->countAllResults(false);

        $number     = ($page <= 0)?null:$page;
        $totalPages = ($size <= 0)?null: ceil($totalElements/$size);
        $firstPage  = $number==="1";
        $lastPage   = $number===strval($totalPages);

        $this->headersConfig();
        return $this->respond(
            [
                'data'=>$products,
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

    public function saveProduct(){
        $this->headersConfig();
        $img    = $this->request->getFile("img");
        $name   = $this->request->getPost('name');
        $unity  = $this->request->getPost('unity');
        $price  = $this->request->getPost('price');

        if ($img != null) {
            $product_photo1 = $img->getRandomName();
            $path =  FCPATH . 'assets';
            $img->move($path, $product_photo1);
        } else {
            $product_photo1 = 'sinimagen.jpeg';
        }
        $data = [
            'pr_name'       => $name,
            'pr_unity'      => $unity,
            'pr_price'      => $price,
            'pr_img'        => base_url() . '/assets/' . $product_photo1
        ];

        $this->productModel->insert($data);
        return $this->respond(['status' => 200], 200);
    }

    public function editProduct(){
        $this->headersConfig();
        $id     = $this->request->getPost('id');
        $img    = $this->request->getFile("img");
        $name   = $this->request->getPost('name');
        $unity  = $this->request->getPost('unity');
        $price  = $this->request->getPost('price');
        if ($img != null) {
            $product_photo1 = $img->getRandomName();
            $path =  FCPATH . 'assets';
            $img->move($path, $product_photo1);
            $data = [
                'pr_name'       => $name,
                'pr_unity'      => $unity,
                'pr_price'      => $price,
                'pr_img'        => base_url() . '/assets/' . $product_photo1
            ];
        } else {
            $data = [
                'pr_name'       => $name,
                'pr_unity'      => $unity,
                'pr_price'      => $price
            ];
        }
            
        $this->productModel->update($id, $data);        
       
        return $this->respond(['status' => 200], 200);
    }
    
}
