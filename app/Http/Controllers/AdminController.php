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


    public function getAllTokens(Request $a)
    {

        $result = $this->adminService->getAllTokens();
        
        if ($result->isEmpty()) {
            return response()->json([
                "message" => "Record not found"
            ], 404);
        }
        $result = $result->toJson(JSON_PRETTY_PRINT);
        return response($result, 200);
    }

    public function revokeToken(Request $a)
    {
        $result = $this->adminService->revokeToken($a->all());
        if ($result == false) {
            return response()->json([
                "message" => "Record not updated"
            ], 404);
        }
        $result = $result->toJson(JSON_PRETTY_PRINT);
        return response($result, 200);
    }

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

                //generate a token and send token and start the session.

                $result = $result->toJson(JSON_PRETTY_PRINT);
                auth()->loginUsingId($user->id);
                return response($result, 200);

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
