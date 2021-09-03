<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:6|max:30',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'cpassword' => 'required|same:password',
        ]);

        try {
            $user = User::create([
                'name'=> $validated['name'],
                'email'=> $validated['email'],
                'password'=> Hash::make($validated['password']),
            ]);
            // generating token
            $response = [
                'user' => $user,
                'accessToken' => $user->createToken('user-token')->plainTextToken,
            ];

            return $this->sendResponse($response, 'User Created Succssfully');
        } catch (\Exception $e){
            return $this->sendError($e->getMessage());
        }

    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ],[
            'email'=> 'Email Or Password is Invalid',
            'password'=> 'Email Or Password is Invalid',
        ]);

        if(Auth::attempt(['email'=>$validated['email'], 'password'=> $validated['password']])){
            $user = auth()->user();
            // generating token
            $response = [
                'user' => $user,
                'accessToken' => $user->createToken('user-token')->plainTextToken,
            ];
            return $this->sendResponse($response, 'Authenticated User');
        }

        return $this->sendError('Email Or Password is Invalid');
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->sendResponse('','Logged out successfuly');
        }catch (\Exception $e){
            return $this->sendError('Something Went Wrong');
        }
    }
}
