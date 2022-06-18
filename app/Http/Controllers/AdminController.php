<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class AdminController
{

    protected $adminService;
    protected $tokenService;


    public function __construct(Services\AdminService $adminService, Services\TokenService $tokenService)
    {
        $this->adminService = $adminService;
        $this->tokenService = $tokenService;
    }

    /**
        * @OA\Get(
        * path="/api/admin/GetAllToken",
        * operationId="GetAllToken",
        * tags={"GetAllToken"},
        * summary="Get All Users Data Active/Inactive",
        * description="Get All Users Data Active/Inactive",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"BearerToken"},
        *               @OA\Property(property="BearerToken", type="text")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Data Fetched Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */ 

    public function getAllTokens(Request $a)
    {

        $result = $this->adminService->getAllTokens();
        
        if ($result->isEmpty()) {
            return response()->json([
                "message" => "Record not found"
            ], 404);
        }
        $result = $result->toJson(JSON_PRETTY_PRINT);
        
        return response()->json([
            "message" => "Data Fetched Successfully" , "data" => $result
        ], 200);
        
    }

  /**
        * @OA\Patch(
        * path="/api/admin/RevokeToken",
        * operationId="RevokeToken",
        * tags={"RevokeToken"},
        * summary="Revoke User Token",
        * description="Revoke User Token",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"token","BearerToken"},
        *               @OA\Property(property="token", type="text"),        
        *               @OA\Property(property="BearerToken", type="text")     
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Updated Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */ 



    public function revokeToken(Request $a)
    {
        $result = $this->adminService->revokeToken($a->all());
        if ($result == false) {
            return response()->json([
                "message" => "Record not updated"
            ], 404);
        }
        $result = $result->toJson(JSON_PRETTY_PRINT);
        return response()->json([
            "message" => "Updated Successfully" , "data" => $result
        ], 200);
        
    }
    
    /**
        * @OA\Get(
        * path="/api/admin-v2/login",
        * operationId="login",
        * tags={"login"},
        * summary="Admin Login ",
        * description="Admin Login",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name","email", "password"},
        *               @OA\Property(property="name", type="text"),
        *               @OA\Property(property="email", type="text")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Token generated successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=403,
        *          description="Unable to generate the token",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */ 

    public function login(Request $a)
    {
        $a->validate([
            "email" => ["required", "email"],
            "password" => ["required", "string", "max:16"]
        ]);

        $user = User::where([["email", $a->input("email")]])->first();
        
        if ($user instanceof User) {
            

            if (Crypt::decrypt($user->password) == $a->password) {
                
                $result = $this->tokenService->processUserForToken($user);
                
                if($result == false){
                    return response()->json([
                        "message" => "Unable to generate the token"
                    ], 403);
                }

                return response()->json([
                    "message" => "Token generated successfully" , "token" => $result
                ], 200);


            } else {

                return response()->json([
                    "message" => "Incorrect Password"
                ], 403);
            }
        } else {
            return response()->json([
                "message" => "Incorrect Details Provided"
            ], 403);
        }
    }
}
