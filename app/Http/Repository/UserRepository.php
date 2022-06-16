<?php

namespace App\Http\Repository;

use App\Models\User;


class UserRepository  extends BaseRepository{

    public function __construct(User $model) {
        parent::__construct($model);
    }

}
