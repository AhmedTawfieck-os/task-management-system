<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Lang;

class AuthController extends Controller
{
    //

    public function login(LoginRequest $request)
    {
        $data= $request->validated(); 
        if( Auth::attempt(['email' => $data['email'], 'password' => $data['password'] ]) ){
            $user= User::where('email', $data['email'])->first(); 
            $token = JWTAuth::fromUser($user);
            return response()->Json(['access_token' => $token,
                                     'token_type' => 'Bearer',
                                     'user' => UserResource::make($user->load('roles'))],200);
        }
        return response()->Json(['message'=> Lang::get('messages.invalid-credentials')],401);
    }

    public function logout()
    {
        auth()->logout(); 
        return response()->Json(['message'=> Lang::get('messages.user-logout')], 200);
    }
}
