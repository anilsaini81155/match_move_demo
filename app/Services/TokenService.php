<?php

namespace App\Services;

use App\Models\User;
use App\Models\Token;
use App\Models\SysConfig;
use Illuminate\Support\Collection as Collect;
use Carbon\Carbon;

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
        DB::beginTransaction();

        try {

            if ($user instanceof User) {

                $result =  SysConfig::where(['name' => Config('commonconfig.Token_Generation_Json_Key'), 'status' => 'Active', 'is_deleted' => 'True'])
                    ->get();

                if ($result->isEmpty()) {
                    return [];
                }

                $data = $result->toArray();

                $data = json_decode(json_encode($data), 1);

                $key = hash('sha256', $data['config']);

                $request = [
                    'mobile_no' => $user->mobile_no,
                    'id' => $user->id,
                    'created_at' => now()
                ];

                $requestData = json_encode($request);

                $token = hash_hmac('sha256', $requestData, $key);

                Token::insert(['user_id' => $user->id, 'expires_at' => Carbon::now()->addDay(config('commonconfig.Token_Expiry'))->format('Y-m-d H:i:s'), 'token' => $token]);

                DB::commit();

                return $token;
            } else {
                return [];
            }
        } catch (\Exception $ex) {
            DB::rollback();
            Log::info($ex);
            return [];
        }
    }
}
