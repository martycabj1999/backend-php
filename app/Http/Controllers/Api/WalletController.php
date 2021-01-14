<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class WalletsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $response['data'] = Wallet::all();

            return \Response::json($response, 200);
        } catch (\Exception $e) {
            \Log::error("Error indexWallet " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine());
            $response['error'] = "Error indexWallet " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine();
            return \Response::json($response, 500);
        }
    }

    public function purchase(Request $request)
    {
        try {

            $response['data'] = $this->updateWallet(
                Auth::user()->id,
                $request['name']
            );
            
            return \Response::json($response, 200);
        } catch (\Exception $e) {
            \Log::error("Error updateWallet " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine());
            $response['error'] = "Error updateWallet " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine();
            return \Response::json($response, 500);
        }
    }
    
    public function purchaseVerified(Request $request)
    {
        try {

            $response['data'] = $this->updateWallet(
                Auth::user()->id,
                $request['name']
            );
            
            return \Response::json($response, 200);
        } catch (\Exception $e) {
            \Log::error("Error updateWallet " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine());
            $response['error'] = "Error updateWallet " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine();
            return \Response::json($response, 500);
        }
    }

}
