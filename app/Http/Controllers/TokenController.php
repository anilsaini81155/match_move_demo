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

    /**
        * @OA\Get(
        * path="/api/open-call/ValidateToken",
        * operationId="ValidateToken",
        * tags={"ValidateToken"},
        * summary="Token Validation",
        * description="Token  Validation here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"bearerToken"},
        *               @OA\Property(property="bearerToken", type="text")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="Token verified successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=403,
        *          description="Invalid Token",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */

    public function validateToken(Request $a)
    {

        $result =  $this->authCheck->checkTokenAuthenticity($a);

        if ($result == false) {
            return response()->json([
                "message" => "Invalid Token"
            ], 403);
        }

        return response()->json([
            "message" => "Token verified successfully"
        ], 200);
    }

    /**
        * @OA\Post(
        * path="/api/admin/CreateToken",
        * operationId="CreateToken",
        * tags={"CreateToken"},
        * summary="User Register | Token Generation",
        * description="User Register here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name","email", "contact_no"},
        *               @OA\Property(property="name", type="text"),
        *               @OA\Property(property="email", type="text"),
        *               @OA\Property(property="contact_no", type="text")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Token generated successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */

    public function createToken(Request $a)
    {

        $a->validate([
            "name" => "required|max:100",
            "email" => "required|email",
            "contact_no" => "required|numeric|digits:10|regex:/^[6-9][0-9]{9}$/"
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
