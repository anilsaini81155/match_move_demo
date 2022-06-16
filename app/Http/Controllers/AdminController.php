<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminService;

class AdminController{

    protected $adminService;


    public function __construct(Services\AdminService $adminService)
    {
        $this->adminService = $adminService;

        
    }


    public function getAllTokens(Request $a){

     $this->adminService->getAllTokens();

     $result = $this->itemService->getItem($a->all());
        if ($result->isEmpty()) {
            return response()->json([
                "message" => "Record not found"
            ], 404);
        }
        $result = $result->toJson(JSON_PRETTY_PRINT);
        return response($result, 200);

    }

    public function revokeToken(Request $a){
        $a->BearerToken;

           $encodingAlgorithm = 'sha256';

            $postData =json_encode($postData);  

            $generatedKey = hash($encodingAlgorithm, $key);
            
            $generatedToken = hash_hmac($encodingAlgorithm, $postData, $generatedKey);
            
            $apiRequestHeader = getallheaders()['Authorization'];


            $result = $this->itemService->updateItem($a->all());
            if ($result == false) {
                return response()->json([
                    "message" => "Record not updated"
                ], 404);
            }
            $result = $result->toJson(JSON_PRETTY_PRINT);
            return response($result, 200);
    }

}
