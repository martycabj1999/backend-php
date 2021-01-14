<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

//Models
use App\User;
use App\Wallet;

//Dependencias
use Mail;
use Carbon\Carbon;

class AuthController extends Controller {

    public function signup(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'identification_number'     => 'required',
            'phone'     => 'required',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string',
        ]);

        $user = new User([
            'name'     => $request->name,
            'identification_number'    => intval($request->identification_number),
            'phone'    => intval($request->phone),
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The email or password are not valid'], 422);
        }

        $user = $request->user();
        
        $wallet = new Wallet([
            'user_id'     => $user->id,
        ]);
        $wallet->save();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        $response['data'] = [
            'access_token' => $tokenResult->accessToken,
            'user' => $user,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
            ];

        return response()->json($response);
    }

    public function login(Request $request)
    {

        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The email or password are not valid'], 422);
        }

        $user = $request->user();
        $role['name'] = 'admin';
        $user['role'] = $role;

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        $response['data'] = [
            'access_token' => $tokenResult->accessToken,
            'user' => $user,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
            ];

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' =>
            'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

}
