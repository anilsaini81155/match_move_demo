<?php

namespace App\Services;

use App\Models\User;
use App\Models\Token;
use App\Models\SysConfig;
use Illuminate\Support\Collection as Collect;

class AdminService
{

    public function __construct()
    {
    }

    public function getAllToken()
    {

        $allTokenResult =  Token::where(['is_revoked' => 2])
            ->where('expire_datetime', '<=', now())
            ->get();

        return $allTokenResult;
    }

    public function revokeToken($a){

        Token::update(['is_reveoked' => 1])
                ->where(['user_id'] => $a->[id]);


    }


}
