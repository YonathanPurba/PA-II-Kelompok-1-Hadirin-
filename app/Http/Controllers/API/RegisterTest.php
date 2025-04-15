<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterTest extends Controller
{
    // Register API - username, password, password_confirmation
    public function register(Request $request)
    {
        $request->validate([
            "username" => "required|string|unique:users,username",
            "password" => "required|confirmed|min:6",
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'dibuat_pada' => now(),
            'id_role' => $request->id_role,
            'dibuat_oleh' => 'api_register',
        ]);

        return response()->json([
            "status" => true,
            "message" => "User registered successfully",
            "data" => $user
        ]);
    }
};