<?php


namespace App\Library;

use App\Contracts\AuthCheck;
use App\Models\SysConfig;

Class AuthCheckLib implements AuthCheck{

    public function __construct()
    {
        
    }

    public function checkTokenAuthenticity($rqst){

        $result =  SysConfig::where(['name' => Config('commonconfig.Token_Generation_Json_Key')]);

            if ($result->isEmpty()) {
                return [];
            }


            $data = $result->toArray();

            $data = json_decode(json_encode($data), 1);

            $key = hash('sha256', $data['config']);

            $request = ['user_id' => $user->id , 'generation_time' => now()];

            $requestData = json_encode($request);

            $token = hash_hmac('sha256', $requestData, $key);




    }




}
