<?php

namespace App\Services;

use App\Models\User;
use App\Models\Token;
use App\Models\SysConfig;
use Illuminate\Support\Collection as Collect;

class TokenService
{

    public function __construct()
    {
    }

    public function processUserForToken($a)
    {

        $userDetails = User::where(['email' => $a->email, 'mobile_no' => $a->mobile_no, 'name' => $a->name])
            ->get();

        if ($userDetails->isNotEmpty()) {

            $userResult =   User::insert(['email' => $a->email, 'mobile_no' => $a->mobile_no, 'name' => $a->name]);
            return  $this->generateToken(User::where(['id' => $userResult])->get());
        } else {
            return  $this->generateToken($userDetails);
        }
    }

    public function generateToken($user)
    {

        if ($user instanceof User) {

            //create one sys json config table 
            //store the key in the table ..

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

            Token::insert(['user_id' => $user->id , 'expiry' => now() .Config('commonconfig.Token_Expiry')]);

            return $token;
        }
    }

    
}
