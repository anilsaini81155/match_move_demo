<?php

namespace App\Http\Repository;

use App\Models\Token;


class TokenRepository  extends BaseRepository{

    public function __construct(Token $model) {
        parent::__construct($model);
    }

}
