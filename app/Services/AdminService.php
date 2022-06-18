<?php

namespace App\Services;

use App\Http\Repository;
use Illuminate\Support\Collection as Collect;
use DB;

class AdminService
{
    protected $tokenRepo;

    public function __construct(Repository\TokenRepository $tokenRepo)
    {
        $this->tokenRepo = $tokenRepo;
    }

    public function getAllTokens()
    {
        return  $this->tokenRepo->getAllTokens();
    }

    public function revokeToken($a)

    {
        $rslt = $this->tokenRepo->getTokenDetails(['token' => $a->token]);
        return  $this->tokenRepo->update(['revoked' => 1], $rslt->id);
    }
}
