<?php

namespace App\Http\Repository;

use DB;
use App\Models\Token;


class TokenRepository  extends BaseRepository
{

    public function __construct(Token $model)
    {
        parent::__construct($model);
    }

    public function getAllTokens()
    {

        $allTokenResult =  $this->model->select('expires_at', DB::raw('Case when revoked = 0 then Active else InActive'), 'sys_user.name')
            ->join('sys_user', 'sys_user.id', 'token.user_id')
            ->where('expires_at', '<=', now())
            ->get();

        return $allTokenResult;
    }


    public function getTokenDetails($data)
    {
        return  $this->model->where($data)->first();
    }
}
