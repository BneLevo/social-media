<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getProfile($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response([
                'message' => 'User not found'
            ], 404);
        }
    
        return response([
            'user' => $user
        ], 200);
    }
}
