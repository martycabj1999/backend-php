<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';

    protected $fillable = [
        'user_id', 'amount'
    ];

    public function users()
    {
        return $this->hasMany('App\User', 'user_id');
    }

    public function findWalletService($identificationNumber, $phone)
    {
        dd($this);
    }

    public function updateWalletService()
    {
    }

    public function purchaseService()
    {
    }

    public function purchaseVerifiedService()
    {
    }

}
