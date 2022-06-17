<?php

namespace App\Services;

use App\Models\User;
use App\Models\Token;
use App\Models\SysConfig;
use Illuminate\Support\Collection as Collect;
use DB;

class AdminService
{

    public function __construct()
    {
    }

    public function getAllTokens()
    {

        $allTokenResult =  Token::select('expires_at', DB::raw('Case when revoked = 0 then Active else InActive'), 'sys_user.name')
            ->join('sys_user', 'sys_user.id', 'token.user_id')
            ->where('expires_at', '<=', now())
            ->get();

        return $allTokenResult;
    }

    public function revokeToken($a)
    {

        return  Token::where('id', $a->id)
            ->update(['is_revoked' => 1]);
    }
}
