<?php

namespace App\Http\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'id_role' => 'required|exists:role,id_role',
            'nomor_telepon' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'id_role' => $request->id_role,
            'nomor_telepon' => $request->nomor_telepon,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'API',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Login user and create token.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah',
            ], 401);
        }

        $user = User::where('username', $request->username)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Update last login
        $user->last_login_at = now();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    /**
     * Logout user (revoke the token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ], 200);
    }

    /**
     * Get the authenticated user.
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        $data = [
            'user' => $user,
        ];

        // Load related data based on role
        if ($user->id_role == 2) { // Guru
            $data['guru'] = $user->guru()->with('mataPelajaran')->first();
        } elseif ($user->id_role == 3) { // Orang Tua
            $data['orang_tua'] = $user->orangTua()->with('siswa')->first();
        } elseif ($user->id_role == 1) { // Staf
            $data['staf'] = $user->staf()->first();
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'nomor_telepon' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $userData = [];

        if ($request->has('nomor_telepon')) {
            $userData['nomor_telepon'] = $request->nomor_telepon;
        }

        if ($request->has('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if (!empty($userData)) {
            $userData['diperbarui_pada'] = now();
            $userData['diperbarui_oleh'] = 'API';
            $user->update($userData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user,
        ], 200);
    }
}
