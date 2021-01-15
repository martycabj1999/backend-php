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

    public function findWallet(Request $request)
    {
        try {

            $wallet = new Wallet();
            $response['data'] = $wallet->findWalletService(
                $request['identification_number'],
                $request['phone']
            );

            if(!$response['data']){
                $response['errors'] = array(
                    "params" => "identification number or phone",
                    "msg" => "invalid identification number or phone",
                );
                return \Response::json($response, 422);
            }

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

            $wallet = new Wallet();

            $response['data'] = $wallet->updateWalletService(
                $request['identification_number'],
                $request['phone'],
                $request['amount']
            );

            if(!$response['data']){
                $response['errors'] = array(
                    "params" => "identification number or phone",
                    "msg" => "invalid identification number or phone",
                );
                return \Response::json($response, 422);
            }
            
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
            $wallet = new Wallet();

            $response['data'] = $wallet->purchaseService(
                Auth::user()->id,
                $request['title'],
                $request['amount']
            );
            
            if(!$response['data']){
                $response['errors'] = array(
                    "params" => "amount",
                    "msg" => "invalid amount",
                );
                return \Response::json($response, 422);
            }

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
            $wallet = new Wallet();

            $response['data'] = $wallet->purchaseVerifiedService(
                $request['code']
            );

            if(!$response['data']){
                $response['errors'] = array(
                    "params" => "code",
                    "msg" => "invalid code",
                );
                return \Response::json($response, 422);
            }
            
            return \Response::json($response, 200);
        } catch (\Exception $e) {
            \Log::error("Error purchaseVerified " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine());
            $response['error'] = "Error purchaseVerified " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine();
            return \Response::json($response, 500);
        }
    }

}
