<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Services;
use Illuminate\Support\Collection as Collect;
use App\Contracts;

class TokenController
{

    protected $tokenService;
    protected $authCheck;

    public function __construct(Services\TokenService $tokenService, Contracts\AuthCheck $authCheck)
    {
        $this->tokenService = $tokenService;
        $this->authCheck = $authCheck;
    }


    public function validateToken(Request $a)
    {

        $result =  $this->authCheck->checkTokenAuthenticity($a);

        if ($result == false) {
            return response()->json([
                "message" => "Unable to verify the token"
            ], 403);
        }

        return response()->json([
            "message" => "Token verified successfully"
        ], 200);
    }

    public function createToken(Request $a)
    {

        $a->validate([
            "name" => "required|max:100",
            "email" => "required|email",
            "mobile_no" => "required|numeric|digits:10|regex:/^[6-9][0-9]{9}$/"
        ]);

        $result = $this->tokenService->processUserForToken($a);

        if ($result == false) {
            return response()->json([
                "message" => "Token not created"
            ], 404);
        }

        return response()->json([
            "message" => "Token generated successfully", "token" => $result
        ], 201);
    }
}
