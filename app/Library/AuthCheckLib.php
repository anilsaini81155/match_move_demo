<?php


namespace App\Library;

use App\Contracts\AuthCheck;
use App\Http\Repository;
use App\Models\SysConfig;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthCheckLib implements AuthCheck
{

    protected $userRepo;
    protected $sysConfigRepo;
    protected $tokenRepo;

    public function __construct(Repository\TokenRepository $tokenRepo, Repository\SysConfigRepository $sysConfigRepo, Repository\UserRepository $userRepo)
    {

        $this->userRepo = $userRepo;
        $this->sysConfigRepo = $sysConfigRepo;
        $this->tokenRepo = $tokenRepo;
    }

    public function checkTokenAuthenticity($rqst)
    {   
        $header = getallheaders()['Authorization'];
        if (Str::startsWith($header, 'Bearer ')) {
            $header = Str::substr($header, 7);
        }

        $getTokenDeatils = $this->tokenRepo->getTokenDetails(['token' => $header, 'revoked' => 0]);

        if ($getTokenDeatils == false) {
            return false;
        }

        $now = Carbon::now();
        $totalDuration =  $getTokenDeatils->initial_rqst_datetime != NULL ? $now->diffInMinutes(Carbon::parse($getTokenDeatils->initial_rqst_datetime)) : 0;


        $getUserDeatils = $this->userRepo->getDetails(['id' => $getTokenDeatils->user_id]);

        if ($getUserDeatils == false) {
            return false;
        }

        if ($totalDuration > 60 && $getTokenDeatils->no_of_attempts == 10) {
            $getUserDeatils->no_of_attempts = $totalDuration = 0;
        }


        if (Carbon::now()->format('Y-m-d H:i:s') < Carbon::parse($getTokenDeatils->expires_at)->format('Y-m-d H:i:s')) {
            
            if ($getUserDeatils->no_of_attempts < $getUserDeatils->max_no_of_rqts_per_hour && $totalDuration <= 60) {
                
                $data =  $this->userRepo->update(['no_of_attempts' => ($getUserDeatils->no_of_attempts + 1)], $getUserDeatils->id);
                
                return True;
            } else {
                return false;
            }
        } else {
            
            return false;
        }
    }
}
