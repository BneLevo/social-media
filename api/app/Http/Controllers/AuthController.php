<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string|alpha_dash|unique:users,username|max:30',
            'display_name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6'
        ]);

        $user = User::create([
            'username' => strtolower($fields['username']),
            'display_name' => $fields['display_name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        $token = $user->createToken('main_token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);

        $loginType = filter_var($fields['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginType, $fields['login'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Wrong email or password'
            ], 401);
        }

        $token = $user->createToken('main_token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 200);
    }
}
