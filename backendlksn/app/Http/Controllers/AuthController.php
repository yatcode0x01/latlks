<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Http\Controllers\API\BaseController as BaseController;

class AuthController extends BaseController
{
    public function signup(Request $request)
    {
        $data = $request->only('username', 'password');
        $validator = Validator::make($data, [
            'username' => 'required|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        if ($validator->fails()) {
            return $this->send_error('Something Wrong!', $validator->messages());
        }

        $user = User::create([
        	'username' => $request->username,
        	'password' => bcrypt($request->password),
            'role' => 'admin',
            'division_id' => 1,
        ]);

        return $this->send_response('User craeted successfully!', $user);
    }
 
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $validator = Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required|string|min:6|max:50'
        ]);

        if ($validator->fails()) {
            return $this->send_error('Something Wrong!', $validator->messages());
        }

        try {
            if (!$token = JWTAuth::attempt($credentials, ['exp' => Carbon::now()->addDays(1)->timestamp])) {
                $data = [
                    'error' => 'Unauthorized'
                ];
                return $this->send_error('Invalid Username/Password!', $data, 401);
            }
        } catch (JWTException $e) {
            return $this->send_error("Couldn't Create Token!", null, 500);
        }

        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => '24 Hours'
        ];
        return $this->send_response('Login successfully!', $data);
    }
 
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate($request->token);
            return $this->send_response('Successfully logged out!', null);
        } catch (JWTException $exception) {
            return $this->send_error('Unauthorized', null, 401);
        }
    }
 
    public function me(Request $request)
    {
        try {
            $user = JWTAuth::authenticate($request->token);
            return $this->send_response('Success get profile!', $user);
        } catch (JWTException $exception) {
            return $this->send_error('Unauthorized', null, 401);
        }
    }

    public function reset_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->send_error('Something Wrong!', $validator->messages());
        }

        try {
            $user = JWTAuth::authenticate($request->token);
            $username = $user->username;
            $credentials = [
                'username' => $username,
                'password' => $request->old_password
            ];

            if (JWTAuth::attempt($credentials)) {
                $find = User::where('username', '=', $username)->first();
                $find->password = bcrypt($request->new_password);
                $find->update();
                JWTAuth::invalidate($request->token);
                return $this->send_response('Reset successfully! User logged out', $user);
            }

            return $this->send_error('Old Password and New Password Not Match', $request->all(), 401);
        } catch (JWTException $e) {
            return $this->send_error("Something Wrong!", null, 500);
        }
    }
}
