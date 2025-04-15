<?php

namespace App\Http\API;

use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrangTuaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orangTua = OrangTua::with(['user', 'siswa'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $orangTua,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'nomor_telepon' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Buat user terlebih dahulu
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'id_role' => 3, // Role orang tua
                'nomor_telepon' => $request->nomor_telepon,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            // Buat data orang tua
            $orangTua = OrangTua::create([
                'id_user' => $user->id_user,
                'nama_lengkap' => $request->nama_lengkap,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            DB::commit();

            // Load relasi untuk response
            $orangTua->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Orang tua berhasil ditambahkan',
                'data' => $orangTua,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan orang tua',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $orangTua = OrangTua::with(['user', 'siswa.kelas'])->find($id);
        
        if (!$orangTua) {
            return response()->json([
                'success' => false,
                'message' => 'Orang tua tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $orangTua,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $orangTua = OrangTua::with('user')->find($id);
        
        if (!$orangTua) {
            return response()->json([
                'success' => false,
                'message' => 'Orang tua tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'string|max:255',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'username' => 'string|max:255|unique:users,username,' . $orangTua->id_user . ',id_user',
            'password' => 'nullable|string|min:6',
            'nomor_telepon' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update user data
            $userData = [];
            
            if ($request->has('username')) {
                $userData['username'] = $request->username;
            }
            
            if ($request->has('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            
            if ($request->has('nomor_telepon')) {
                $userData['nomor_telepon'] = $request->nomor_telepon;
            }
            
            if (!empty($userData)) {
                $userData['diperbarui_pada'] = now();
                $userData['diperbarui_oleh'] = 'API';
                $orangTua->user->update($userData);
            }

            // Update orang tua data
            $orangTuaData = [];
            
            if ($request->has('nama_lengkap')) {
                $orangTuaData['nama_lengkap'] = $request->nama_lengkap;
            }
            
            if ($request->has('alamat')) {
                $orangTuaData['alamat'] = $request->alamat;
            }
            
            if ($request->has('pekerjaan')) {
                $orangTuaData['pekerjaan'] = $request->pekerjaan;
            }
            
            if (!empty($orangTuaData)) {
                $orangTuaData['diperbarui_pada'] = now();
                $orangTuaData['diperbarui_oleh'] = 'API';
                $orangTua->update($orangTuaData);
            }

            DB::commit();

            // Refresh model untuk mendapatkan data terbaru
            $orangTua = OrangTua::with(['user', 'siswa'])->find($id);

            return response()->json([
                'success' => true,
                'message' => 'Orang tua berhasil diperbarui',
                'data' => $orangTua,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui orang tua',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $orangTua = OrangTua::find($id);
        
        if (!$orangTua) {
            return response()->json([
                'success' => false,
                'message' => 'Orang tua tidak ditemukan',
            ], 404);
        }
        
        DB::beginTransaction();
        try {
            // Simpan id_user untuk menghapus user setelah orang tua dihapus
            $idUser = $orangTua->id_user;
            
            // Hapus orang tua
            $orangTua->delete();
            
            // Hapus user terkait
            User::where('id_user', $idUser)->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Orang tua berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus orang tua',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get siswa by orang tua.
     */
    public function getSiswa($id)
    {
        $orangTua = OrangTua::find($id);
        
        if (!$orangTua) {
            return response()->json([
                'success' => false,
                'message' => 'Orang tua tidak ditemukan',
            ], 404);
        }
        
        $siswa = $orangTua->siswa()->with('kelas')->get();
        
        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }
}
