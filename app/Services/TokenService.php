<?php

namespace App\Services;

use App\Models\User;
use DB;
use Illuminate\Support\Collection as Collect;
use Carbon\Carbon;
use App\Http\Repository;
use Log;
use Config;

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
        $userDetails = $this->userRepo->getDetails(['email' => $a->email, 'contact_no' => $a->contact_no, 'name' => $a->name]);

        if ($userDetails == false) {

            $userResult =   $this->userRepo->insert(['email' => $a->email, 'contact_no' => $a->contact_no, 'name' => $a->name]);
            return  $this->generateToken(User::where(['id' => $userResult])->get());
        } else {

            return  $this->generateToken($userDetails);
        }
    }

    public function generateToken($user)
    {
        DB::beginTransaction();

        try {

            $data = $this->sysConfigRepo->getSysDetails(['name' => Config('commonconfig.Token_Generation_Json_Key'), 'status' => 'Active', 'is_deleted' => 'False']);
            
            if ($data == false) {
                return false;
            }
            
            $key = hash('sha256', $data->config);
            $curr_time= now() ;
            $request = [
                'contact_no' => $user->contact_no,
                'id' => $user->id,
                'created_at' => $curr_time
            ];

            $requestData = json_encode($request);
            
            $token = hash_hmac('sha256', $requestData, $key);
            
            $this->tokenRepo->insert(['user_id' => $user->id, 'expires_at' => Carbon::now()->addDay(config('commonconfig.Token_Expiry'))->format('Y-m-d H:i:s'), 'token' => $token ,'revoked' => 0 ,'created_at' => $curr_time]);
        
            DB::commit();

            return $token;
        } catch (\Exception $ex) {
            
            DB::rollback();
            Log::info($ex);
            return false;
        }
    }
}
