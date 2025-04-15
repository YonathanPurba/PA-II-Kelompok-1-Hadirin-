<?php

namespace App\Http\Controllers\API;

use App\Models\Staf;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StafController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staf = Staf::with('user')->get();
        
        return response()->json([
            'success' => true,
            'data' => $staf,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50|unique:staf,nip',
            'jabatan' => 'nullable|string|max:255',
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
                'id_role' => 1, // Role staf
                'nomor_telepon' => $request->nomor_telepon,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            // Buat data staf
            $staf = Staf::create([
                'id_user' => $user->id_user,
                'nama_lengkap' => $request->nama_lengkap,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            DB::commit();

            // Load relasi untuk response
            $staf->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Staf berhasil ditambahkan',
                'data' => $staf,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan staf',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $staf = Staf::with('user')->find($id);
        
        if (!$staf) {
            return response()->json([
                'success' => false,
                'message' => 'Staf tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $staf,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $staf = Staf::with('user')->find($id);
        
        if (!$staf) {
            return response()->json([
                'success' => false,
                'message' => 'Staf tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'string|max:255',
            'nip' => 'nullable|string|max:50|unique:staf,nip,' . $id . ',id_staf',
            'jabatan' => 'nullable|string|max:255',
            'username' => 'string|max:255|unique:users,username,' . $staf->id_user . ',id_user',
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
                $staf->user->update($userData);
            }

            // Update staf data
            $stafData = [];
            
            if ($request->has('nama_lengkap')) {
                $stafData['nama_lengkap'] = $request->nama_lengkap;
            }
            
            if ($request->has('nip')) {
                $stafData['nip'] = $request->nip;
            }
            
            if ($request->has('jabatan')) {
                $stafData['jabatan'] = $request->jabatan;
            }
            
            if (!empty($stafData)) {
                $stafData['diperbarui_pada'] = now();
                $stafData['diperbarui_oleh'] = 'API';
                $staf->update($stafData);
            }

            DB::commit();

            // Refresh model untuk mendapatkan data terbaru
            $staf = Staf::with('user')->find($id);

            return response()->json([
                'success' => true,
                'message' => 'Staf berhasil diperbarui',
                'data' => $staf,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui staf',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $staf = Staf::find($id);
        
        if (!$staf) {
            return response()->json([
                'success' => false,
                'message' => 'Staf tidak ditemukan',
            ], 404);
        }
        
        DB::beginTransaction();
        try {
            // Simpan id_user untuk menghapus user setelah staf dihapus
            $idUser = $staf->id_user;
            
            // Hapus staf
            $staf->delete();
            
            // Hapus user terkait
            User::where('id_user', $idUser)->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Staf berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus staf',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
