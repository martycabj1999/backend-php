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

        if(!$user){
            return false;
        }
                        
        $wallet = Wallet::find($user->id);

        if(!$wallet){
            return false;
        }

        return $wallet;
    }

    public function updateWalletService($identificationNumber, $phone, $amount)
    {
        $user = User::where('identification_number', $identificationNumber)
                        ->where('phone', $phone)
                        ->first();

        if(!$user){
            return false;
        }
                        
        $wallet = Wallet::find($user->id);

        if(!$wallet){
            return false;
        }

        $wallet->amount = $wallet->amount + $amount;
        $wallet->save();

        return $wallet;
    }

    public function purchaseService($userId, $title, $amount)
    {
        $user = User::find($userId);

        if(!$user){
            return false;
        }
                        
        $wallet = Wallet::find($user->id);

        if(!$wallet){
            return false;
        }

        if( $wallet->amount >= $amount){

            $purchase = new Purchase();

            $permitted_chars = '123456890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $code = substr(str_shuffle($permitted_chars), 0, 6);

            $purchase->title = $title;
            $purchase->amount = $amount;
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

        if(!$purchase){
            return false;
        }
                        
        $wallet = Wallet::find($purchase->user_id);
        
        if(!$wallet){
            return false;
        }

        if( $wallet->amount >= $purchase->amount){
            $purchase->verified = true;
            $purchase->save();

            $wallet->amount = $wallet->amount - $purchase->amount;
            $wallet->save();
        }

        return $wallet;
    }

}
