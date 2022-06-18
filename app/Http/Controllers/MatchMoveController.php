<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;

class MatchMoveController
{



    public function __construct()
    {
    }


    public function dashboard(Request $a)
    {
        return response()->json([
            "message" => "Welcome to the dashboard"
        ], 200);
    }
}
