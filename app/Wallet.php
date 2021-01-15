<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Purchase;

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
        $user = User::where('identification_number', $identificationNumber)
                        ->where('phone', $phone)
                        ->first();
                        
        $wallet = Wallet::find($user->id);

        return $wallet;
    }

    public function updateWalletService($identificationNumber, $phone, $mount)
    {
        $user = User::where('identification_number', $identificationNumber)
                        ->where('phone', $phone)
                        ->first();
                        
        $wallet = Wallet::find($user->id);

        $wallet->mount = $wallet->mount + $mount;
        $wallet->save();

        return $wallet;
    }

    public function purchaseService($userId, $title, $mount)
    {
        $user = User::find($userId);
                        
        $wallet = Wallet::find($user->id);

        if( $wallet->mount >= $mount){

            $purchase = new Purchase();

            $permitted_chars = '123456890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $code = substr(str_shuffle($permitted_chars), 0, 6);

            $purchase->title = $title;
            $purchase->mount = $mount;
            $purchase->code = $code;
            $purchase->user_id = $user->id;
            $purchase->save();
        }

        return $wallet;
    }

    public function purchaseVerifiedService($code)
    {
        $purchase = Purchase::where('code', $code)
                        ->where('verified', false)
                        ->first();
                        
        $wallet = Wallet::find($purchase->user_id);

        if( $wallet->mount >= $purchase->mount){
            $purchase->verified = true;
            $purchase->save();

            $wallet->mount = $wallet->mount - $purchase->mount;
            $wallet->save();
        }

        return $wallet;
    }

}
