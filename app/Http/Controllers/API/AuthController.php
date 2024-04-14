<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Models\User;

class AuthController extends Controller
{
    //

    public function login(LoginRequest $request)
    {
        $data= $request->validated(); 
        if( Auth::attempt(['email' => $data['email'], 'password' => $data['password'] ]) ){
            $user= User::where('email', $data['email'])->first(); 
            Auth::login($user); 
            $token= $user->createToken('bearer')->plainTextToken;
            return response()->Json(['access_token' => $token,
                                     'token_type' => 'Bearer',
                                     'user' => UserResource::make($user->load('roles'))],200);
        }
        return response()->Json(['message'=> 'Invalid Credetials'],401);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete(); 
        return response()->Json(['message'=> 'User logged out successfully'], 200);
    }
}
