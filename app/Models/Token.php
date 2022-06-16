<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model {

    protected $table = "token";
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

}


//id
//user_id
//role enum(Admin,User)
//revoked enum (0,1) default 0
//expire datetime
