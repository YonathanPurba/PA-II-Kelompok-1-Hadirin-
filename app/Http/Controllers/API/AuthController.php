<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser;

    /**
     * Login user and create token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Username atau password salah', 401);
        }

        // Update last login time
        $user->last_login_at = now();
        $user->save();

        // Get user role
        $role = $user->role ? $user->role->role : null;

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Get additional user info based on role
        $userInfo = null;
        if ($role === 'guru') {
            $userInfo = $user->guru()->with('mataPelajaran')->first();
        } elseif ($role === 'orangtua') {
            $userInfo = $user->orangtua()->with('siswa.kelas')->first();
        } elseif ($role === 'staf') {
            $userInfo = $user->staf;
        }

        $userData = [
            'id_user' => $user->id_user,
            'username' => $user->username,
            'id_role' => $user->id_role,
            'role' => $role,
            'nomor_telepon' => $user->nomor_telepon,
            'last_login_at' => $user->last_login_at,
            'info' => $userInfo
        ];

        return $this->successResponse([
            'user' => $userData,
            'token' => $token
        ], 'Login berhasil');
    }

    /**
     * Logout user (revoke token)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout berhasil');
    }

    /**
     * Get authenticated user info
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $role = $user->role ? $user->role->role : null;

        // Get additional user info based on role
        $userInfo = null;
        if ($role === 'guru') {
            $userInfo = $user->guru()->with('mataPelajaran')->first();
        } elseif ($role === 'orangtua') {
            $userInfo = $user->orangtua()->with('siswa.kelas')->first();
        } elseif ($role === 'staf') {
            $userInfo = $user->staf;
        }

        $userData = [
            'id_user' => $user->id_user,
            'username' => $user->username,
            'id_role' => $user->id_role,
            'role' => $role,
            'nomor_telepon' => $user->nomor_telepon,
            'last_login_at' => $user->last_login_at,
            'info' => $userInfo
        ];

        return $this->successResponse($userData, 'Data user berhasil diambil');
    }
    
    /**
     * Change password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->errorResponse('Password saat ini tidak sesuai', 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->successResponse(null, 'Password berhasil diubah');
    }
}