<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    // Menampilkan formulir login
    public function showLoginForm()
    {
        return view('login');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:guru,staf,orang_tua',
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
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
            'role' => $request->role,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
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

    //Login Done 
    public function processLogin(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.'
        ]);

        // Cek kredensial login berdasarkan username
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate(); // Hindari session fixation

            $user = User::find(Auth::id());

            // Simpan waktu login terakhir
            if ($user) {
                $user->last_login_at = now();
                $user->save();
            }

            return view('admin.pages.beranda', compact('user'));
        }

        // Jika gagal login, redirect kembali dengan error
        return redirect('/')->withInput()->withErrors([
            'login' => 'Username atau password salah.'
        ]);
    }

    public function logout(Request $request)
    {
        // Logout pengguna dari session
        Auth::logout();

        // Invalidate session dan regenerasi token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman utama atau login
        return redirect('login')->with('message', 'Anda telah logout.');
    }


    public function profile(Request $request)
    {
        $user = $request->user();

        $data = [
            'user' => $user,
        ];

        // Load related data based on role
        if ($user->role == 'guru') {
            $data['guru'] = $user->guru()->with('mataPelajaran')->first();
        } elseif ($user->role == 'orang_tua') {
            $data['orang_tua'] = $user->orangTua()->with('siswa')->first();
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
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

        if ($request->has('no_telepon')) {
            $userData['no_telepon'] = $request->no_telepon;
        }

        if ($request->has('alamat')) {
            $userData['alamat'] = $request->alamat;
        }

        if ($request->has('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if (!empty($userData)) {
            $user->update($userData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user,
        ], 200);
    }
}




    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validasi gagal',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     if (!Auth::attempt($request->only('username', 'password'))) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Username atau password salah',
    //         ], 401);
    //     }

    //     $user = User::where('username', $request->username)->firstOrFail();
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     // Update last login
    //     $user->last_login = now();
    //     $user->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Login berhasil',
    //         'data' => $user,
    //         'access_token' => $token,
    //         'token_type' => 'Bearer',
    //     ], 200);
    // }

    // public function login(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     // Cari user berdasarkan email
    //     $user = User::where('email', $request->email)->first();

    //     // Jika user tidak ditemukan atau password salah
    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     // Buat token untuk user
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     // Simpan waktu login terakhir
    //     $user->update(['dibuat_pada' => now()]);

    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'Bearer',
    //         'user' => [
    //             'id' => $user->id_user,
    //             'nama' => $user->nama,
    //             'email' => $user->email,
    //             'role' => $user->id_role,
    //         ]
    //     ]);
    // }


       // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();
    //         return redirect()->intended('admin/beranda'); // Halaman setelah login
    //     }

    //     return back()->with('error', 'Email atau password salah.');
    // }
