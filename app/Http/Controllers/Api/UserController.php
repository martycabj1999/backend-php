<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Mail;

//Models
use App\User;

//Dependencies
use Response;
use Carbon\Carbon;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $response['data'] = User::all();

            return \Response::json($response, 200);
        } catch (\Exception $e) {
            \Log::error("Error indexUser " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine());
            $response['error'] = "Error indexUser " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine();
            return \Response::json($response, 500);
        }
    }

    public function update(Request $request)
    {
        try {

            $response['data'] = $this->updateUser(
                Auth::user()->id,
                $request['name']
            );

            return \Response::json($response, 200);
        } catch (\Exception $e) {
            \Log::error("Error updateUser " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine());
            $response['error'] = "Error updateUser " . $e->getMessage() . " in file " . $e->getFile() . "@" . $e->getLine();
            return \Response::json($response, 500);
        }
    }

}
