<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Mail;

//Models
use App\User;
use App\Purchase;
use App\Wallet;

//Dependencies
use Response;
use Carbon\Carbon;

class WalletController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function findWallet()
    {
        dd('asdas');
        try {

            $response['data'] = Wallet::findWalletService(
                $request['identification_number'],
                $request['phone']
            );

            return \Response::json($response, 200);
        } catch (\Exception $e) {
            \Log::error("Error indexWallet " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine());
            $response['error'] = "Error indexWallet " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine();
            return \Response::json($response, 500);
        }
    }

    public function updateWallet(Request $request)
    {
        try {

            $response['data'] = Wallet::updateWalletService(
                $request['identification_number'],
                $request['phone'],
                $request['amount']
            );
            
            return \Response::json($response, 200);
        } catch (\Exception $e) {
            \Log::error("Error updateWallet " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine());
            $response['error'] = "Error updateWallet " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine();
            return \Response::json($response, 500);
        }
    }

    public function purchase(Request $request)
    {
        try {

            $response['data'] = Wallet::purchaseService(
                Auth::user()->id,
                $request['title'],
                $request['amount']
            );
            
            return \Response::json($response, 200);
        } catch (\Exception $e) {
            \Log::error("Error purchase " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine());
            $response['error'] = "Error purchase " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine();
            return \Response::json($response, 500);
        }
    }

    public function purchaseVerified(Request $request)
    {
        try {

            $response['data'] = Wallet::purchaseVerifiedService(
                Auth::user()->id,
                $request['code']
            );

            if($response['data']){
                return \Response::json($response, 200);
            }
            
            return \Response::json($response, 422);
        } catch (\Exception $e) {
            \Log::error("Error purchaseVerified " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine());
            $response['error'] = "Error purchaseVerified " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine();
            return \Response::json($response, 500);
        }
    }

}
