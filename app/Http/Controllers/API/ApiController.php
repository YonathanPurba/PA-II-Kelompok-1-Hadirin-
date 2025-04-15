<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
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
            'id_role' => 1,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'api_register',
        ]);

        return response()->json([
            "status" => true,
            "message" => "User registered successfully",
            "data" => $user
        ]);
    }

    // Login API
    public function login(Request $request)
    {
        $request->validate([
            "username" => "required|string",
            "password" => "required"
        ]);

        // Cari user berdasarkan username
        $user = User::where("username", $request->username)->first();

        if ($user) {
            // Cek password
            if (Hash::check($request->password, $user->password)) {

                $token = $user->createToken("myToken")->plainTextToken;

                return response()->json([
                    "status" => true,
                    "message" => "Logged in successfully",
                    "token" => $token,
                    "user" => $user
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Password is incorrect"
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Username not found"
            ]);
        }
    }

    // Profile API
    public function profile()
    {
        $user = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => $user,
            "id" => $user->id_user // gunakan primary key custom
        ]);
    }

    // Logout API
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "status" => true,
            "message" => "User logged out"
        ]);
    }
}
