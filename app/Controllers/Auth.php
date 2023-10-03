<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Firebase\JWT\JWT;

class Auth extends ResourceController
{

	protected $format = 'json';

	public function create()
	{

		$this->headersConfig();
		
		/**
		 * JWT claim types
		 * https://auth0.com/docs/tokens/concepts/jwt-claims#reserved-claims
		 */

		$email = $this->request->getPost('email');
		$password = md5($this->request->getPost('password')==null?"":$this->request->getPost('password'));

		$db      = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('*');
		$builder->where("us_email", $email);
		$builder->where("us_password", $password);
        $query = $builder->get();
        //echo json_encode($query->getResult());


		// add code to fetch through db and check they are valid
		// sending no email and password also works here because both are empty
		if (count($query->getResult()) > 0) {
            $time = time();
			$key = Services::getSecretKey();
			$payload = [
                'iat' => $time,
                'exp' => $time + 60*60*8, // 8 hrs
                'data' => ['email'=>$email,'name'=>$query->getResult()[0]->us_name]
			];

			/**
			 * IMPORTANT:
			 * You must specify supported algorithms for your application. See
			 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
			 * for a list of spec-compliant algorithms.
			 */
			$jwt = JWT::encode($payload, $key);
			return $this->respond(['token' => $jwt, 'username'=>$query->getResult()[0]->us_name], 200);
		}

		return $this->respond(['message' => 'Invalid login details'], 401);
    }
    
    public function validateToken($token){
        try{
            $key = Services::getSecretKey();
            return JWT::decode($token,$key,array('HS256'));
        }catch(\Exception $e){
            return false;
        }
    }

    public function verifyToken(){
         $key = Services::getSecretKey();
         $token = $this->request->getPost("token");
        
         if($this->validateToken($token) == false){
             return $this->respond(['message'=>'Token Invalido'],401);
         }else{
             $data = JWT::decode($token,$key,array('HS256'));
             return $this->respond(['data'=>$data],200);
         }

    }

	public function headersConfig()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
	}

	//function que es llamada en routes para todos los controladores
    public function options()
    {
        return $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
            ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
            ->setStatusCode(200); //status code
    }

}