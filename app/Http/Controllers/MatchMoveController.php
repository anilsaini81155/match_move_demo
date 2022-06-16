<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;

class TokenController{



    public function __construct()
    {
        
    }


    public function validateToken(Request $a){


    }

    public function createToken(Request $a){

        $a->user_name;
        $a->mobile_no;
        $a->email;

        //insert above
        //insert above



    }

}


?>