<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';

    public function users()
    {
        return $this->hasMany('App\User', 'user_id');
    }

}
