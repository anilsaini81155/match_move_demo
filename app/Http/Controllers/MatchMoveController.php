<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;

class MatchMoveController
{



    public function __construct()
    {
    }

    
    /**
        * @OA\Get(
        * path="/api/matchMove/Dashboard",
        * operationId="Dashboard",
        * tags={"Dashboard"},
        * summary="User Dashboard",
        * description="User Dashboard",
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
        *          description="Welcome to dashboard",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */ 

    public function dashboard(Request $a)
    {
        return response()->json([
            "message" => "Welcome to the dashboard"
        ], 200);
    }
}
