<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterTest extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'id_role'  => 'required|integer|exists:role,id_role' // Perhatikan nama tabel 'roles'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'id_role'      => $request->id_role,
            'dibuat_pada'  => now(),
            'dibuat_oleh'  => 'system'
        ]);

        return response()->json([
            'success' => true,
            'data'    => $user,
            'message' => 'User registered successfully'
        ], 201);
    }
}
