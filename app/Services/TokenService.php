<?php

namespace App\Services;

use App\Models\User;
use DB;
use Illuminate\Support\Collection as Collect;
use Carbon\Carbon;
use App\Http\Repository;

class TokenService
{

    protected $userRepo;
    protected $sysConfigRepo;
    protected $tokenRepo;

    public function __construct(Repository\UserRepository $userRepo, Repository\SysConfigRepository $sysConfigRepo, Repository\TokenRepository $tokenRepo)
    {
        $this->userRepo = $userRepo;
        $this->sysConfigRepo = $sysConfigRepo;
        $this->tokenRepo = $tokenRepo;
    }

    public function processUserForToken($a)
    {
        $userDetails = $this->userRepo->getDetails(['email' => $a->email, 'mobile_no' => $a->mobile_no, 'name' => $a->name]);


        if ($userDetails->isNotEmpty()) {

            $userResult =   $this->userRepo->insert(['email' => $a->email, 'mobile_no' => $a->mobile_no, 'name' => $a->name]);
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

                $result = $this->sysConfigRepo->getSysDetails(['name' => Config('commonconfig.Token_Generation_Json_Key'), 'status' => 'Active', 'is_deleted' => 'True']);

                if ($result->isEmpty()) {
                    return false;
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

                $this->tokenRepo->insert(['user_id' => $user->id, 'expires_at' => Carbon::now()->addDay(config('commonconfig.Token_Expiry'))->format('Y-m-d H:i:s'), 'token' => $token]);

                DB::commit();

                return $token;
            } else {
                return false;
            }
        } catch (\Exception $ex) {
            DB::rollback();
            Log::info($ex);
            return false;
        }
    }
}
