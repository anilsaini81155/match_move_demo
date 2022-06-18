<?php


namespace App\Library;

use App\Contracts\AuthCheck;
use App\Http\Repository;
use App\Models\SysConfig;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;

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
        $result = $this->sysConfigRepo->getSysDetails(['name' => Config('commonconfig.Token_Generation_Json_Key'), 'status' => 'Active', 'is_deleted' => 'True']);

        if ($result->isEmpty()) {
            return false;
        }

        $data = $result->toArray();
        $data = json_decode(json_encode($data), 1);
        $key = hash('sha256', $data['config']);

        $getTokenDeatils = $this->tokenRepo->getTokenDetails(['token' => $rqst->bearerToken(), 'revoked' => 0]);

        if ($getTokenDeatils == false) {
            return false;
        }

        $now = Carbon::now();
        $totalDuration =  $getTokenDeatils->initial_rqst_datetime != NULL ? $now->diffInMinutes(Carbon::parse($getTokenDeatils->initial_rqst_datetime)) : 0;

        if ($totalDuration > 60 && $getTokenDeatils->no_of_attempts == 10) {
            $getTokenDeatils->no_of_attempts = $totalDuration = 0;
        }

        $getUserDeatils = $this->userRepo->getDetails(['mobile_no' => $getTokenDeatils->user_id]);

        if ($getUserDeatils->isEmpty()) {
            return false;
        }

        $request = [
            'mobile_no' => $getUserDeatils->mobile_no,
            'id' => $getTokenDeatils->user_id,
            'created_at' => $getTokenDeatils->created_at
        ];

        $requestData = json_encode($request);

        $token = hash_hmac('sha256', $requestData, $key);

        if (hash_equals($token, $rqst->bearerToken())) {

            if (Carbon::now()->format('Y-m-d H:i:s') < Carbon::parse($getTokenDeatils->expires_at)->format('Y-m-d H:i:s')) {

                if ($getTokenDeatils->no_of_attempts < $getTokenDeatils->max_no_of_rqts_per_hour && $totalDuration <= 60) {

                    $getTokenDeatils = $this->tokenRepo->update(['no_of_attempts' => ($getTokenDeatils->no_of_attempts + 1)], $getTokenDeatils->id);

                    return True;
                }
                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
