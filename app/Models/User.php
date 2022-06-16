<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $table = "user";
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    //id
    //name
    //email
    //mobile_no
    //status
    //is_deleted
    //created_at
    //updated_at
    

}
