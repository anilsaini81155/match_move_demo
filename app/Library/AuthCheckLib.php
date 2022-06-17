<?php


namespace App\Library;

use App\Contracts\AuthCheck;
use App\Models\SysConfig;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;

class AuthCheckLib implements AuthCheck
{

    public function __construct()
    {
    }

    public function checkTokenAuthenticity($rqst)
    {

        $result =  SysConfig::where(['name' => Config('commonconfig.Token_Generation_Json_Key'), 'status' => 'Active', 'is_deleted' => 'True'])->get();

        if ($result->isEmpty()) {
            return false;
        }

        $data = $result->toArray();
        $data = json_decode(json_encode($data), 1);
        $key = hash('sha256', $data['config']);

        $getTokenDeatils = Token::where(['token' => $rqst->bearerToken() ,'revoked' => 0])->first();

        if ($getTokenDeatils->isEmpty()) {
            return false;
        }

        $getUserDeatils = User::where(['mobile_no' => $getTokenDeatils->user_id])->first();

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
                return True;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
