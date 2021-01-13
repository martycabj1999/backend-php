<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
//Models
use App\Models\User;


//Dependencias
use Mail;
use Carbon\Carbon;

class AuthController extends Controller {

    public function signup(Request $request)
    {
        $request->validate([
            /*'name'     => 'required|string',
            'last_name'     => 'required|string',*/
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string',
        ]);
        $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $name = substr(str_shuffle($permitted_chars), 0, 6);
        $user = new User([
            'name'     => $name,
            //'last_name'     => $request->last_name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'active' => true,
            'role_id' => 2
        ]);
        $user->save();

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The email or password are not valid'], 422);
        }

        $user = $request->user();

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

        $users = User::find($user->id)
        ->with('profession')
        ->with('genre')
        ->with('audiovisual_resource')
        ->with('study_level')
        ->get();

        foreach ($users as $u) {
            if($u->id === $user->id){
                $user = $u;
            }
        }

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

    public function loginGoogle(Request $request)
    {
        $user = User::where('email', $request->email)
            ->first();

        if(!$user){
            $name = explode("@", $request->email);
            $user = new User([
                'name'     => $name[0],
                'email'    => $request->email,
                'google_id'    => $request->user_id,
                'active' => true,
                'role_id' => 2
            ]);
            $user->save();
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->expires_at = Carbon::now()->addWeeks(1);

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

    protected function sendResetPasswordEmail(Request $request)
    {
        try {

            //Mail para la Contraseña//
            $user = User::where('email',$request->email)->first();

            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $token = substr(str_shuffle($permitted_chars), 0, 50);

            $user->token_password = $token;

            $user->save();

            Mail::send('email.recover-password', ['user' => $user], function ($m) use ($user) {
                $m->from('masses@app.com', 'Recover password');
                $m->to($user->email)->subject('Recover password!');
            });

            $data['message'] = "Recover password";
            return \Response::json($data, 200);
        } catch ( \Exception $e) {
            \Log::error("Error sendResetLinkEmail ".$e->getMessage()." in file ".$e->getFile()."@".$e->getLine());
        }

    }

    protected function changePassword(Request $request)
    {
        try {

            $user = User::where('token_password', $request->token_password)->first();

            $user->password = bcrypt($request->new_password);
            $user->token_password = null;

            $user->save();

            Mail::send('email.thanks', ['user' => $user], function ($m) use ($user) {
                $m->from('masses@app.com', 'Password restored');
                $m->to($user->email)->subject('Password restored');
            });

            $data['message'] = "Password restored";
            return \Response::json($data, 200);
        } catch ( \Exception $e) {
            \Log::error("Error changePassword ".$e->getMessage()." in file ".$e->getFile()."@".$e->getLine());
            throw new \Exception($e);
        }

    }

    public function loginFacebook(Request $request)
    {
        $user = User::where('facebook_id', $request->user_id)->first();

        if(!$user){
            $user = new User([
                'name'     => $request->name,
                //'facebook_id'    => $request->user_id,
                'active' => true,
                'role_id' => 2
            ]);
            $user->save();
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->expires_at = Carbon::now()->addWeeks(1);

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

}
