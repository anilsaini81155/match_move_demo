<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Services;
use Illuminate\Support\Collection as Collect;

class TokenController
{

    protected $tokenService;

    public function __construct(Services\TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }


    public function validateToken(Request $a)
    {
        $a->validate([
            "BearerToken" => "required"
        ]);

        $result = $this->itemService->getItem($a->all());
        if ($result->isEmpty()) {
            return response()->json([
                "message" => "Record not found"
            ], 404);
        }
        $result = $result->toJson(JSON_PRETTY_PRINT);
        return response($result, 200);

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
        $result = $result->toJson(JSON_PRETTY_PRINT);
        return response($result, 201);



    }
}
