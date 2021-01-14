<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';

    public function users()
    {
        return $this->hasMany('App\User', 'user_id');
    }
}
