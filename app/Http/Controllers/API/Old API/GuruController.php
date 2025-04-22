<?php

namespace App\Http\Controllers\API;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guru = Guru::with(['user', 'mataPelajaran', 'kelas'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $guru,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50|unique:guru,nip',
            'bidang_studi' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'nomor_telepon' => 'nullable|string|max:15',
            'mata_pelajaran' => 'nullable|array',
            'mata_pelajaran.*' => 'exists:mata_pelajaran,id_mata_pelajaran',
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
                'id_role' => 2, // Role guru
                'nomor_telepon' => $request->nomor_telepon,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            // Buat data guru
            $guru = Guru::create([
                'id_user' => $user->id_user,
                'nama_lengkap' => $request->nama_lengkap,
                'nip' => $request->nip,
                'bidang_studi' => $request->bidang_studi,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            // Jika ada mata pelajaran, tambahkan relasi
            if ($request->has('mata_pelajaran') && is_array($request->mata_pelajaran)) {
                $mataPelajaranData = [];
                foreach ($request->mata_pelajaran as $idMataPelajaran) {
                    $mataPelajaranData[$idMataPelajaran] = [
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'API',
                    ];
                }
                $guru->mataPelajaran()->attach($mataPelajaranData);
            }

            DB::commit();

            // Load relasi untuk response
            $guru->load(['user', 'mataPelajaran']);

            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil ditambahkan',
                'data' => $guru,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan guru',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $guru = Guru::with(['user', 'mataPelajaran', 'jadwal.kelas', 'jadwal.mataPelajaran'])->find($id);
        
        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $guru,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $guru = Guru::with('user')->find($id);
        
        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'string|max:255',
            'nip' => 'nullable|string|max:50|unique:guru,nip,' . $id . ',id_guru',
            'bidang_studi' => 'nullable|string|max:255',
            'username' => 'string|max:255|unique:users,username,' . $guru->id_user . ',id_user',
            'password' => 'nullable|string|min:6',
            'nomor_telepon' => 'nullable|string|max:15',
            'mata_pelajaran' => 'nullable|array',
            'mata_pelajaran.*' => 'exists:mata_pelajaran,id_mata_pelajaran',
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
                $guru->user->update($userData);
            }

            // Update guru data
            $guruData = [];
            
            if ($request->has('nama_lengkap')) {
                $guruData['nama_lengkap'] = $request->nama_lengkap;
            }
            
            if ($request->has('nip')) {
                $guruData['nip'] = $request->nip;
            }
            
            if ($request->has('bidang_studi')) {
                $guruData['bidang_studi'] = $request->bidang_studi;
            }
            
            if (!empty($guruData)) {
                $guruData['diperbarui_pada'] = now();
                $guruData['diperbarui_oleh'] = 'API';
                $guru->update($guruData);
            }

            // Update mata pelajaran jika ada
            if ($request->has('mata_pelajaran')) {
                $mataPelajaranData = [];
                foreach ($request->mata_pelajaran as $idMataPelajaran) {
                    $mataPelajaranData[$idMataPelajaran] = [
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'API',
                    ];
                }
                $guru->mataPelajaran()->sync($mataPelajaranData);
            }

            DB::commit();

            // Refresh model untuk mendapatkan data terbaru
            $guru = Guru::with(['user', 'mataPelajaran'])->find($id);

            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil diperbarui',
                'data' => $guru,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui guru',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $guru = Guru::find($id);
        
        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }
        
        DB::beginTransaction();
        try {
            // Simpan id_user untuk menghapus user setelah guru dihapus
            $idUser = $guru->id_user;
            
            // Hapus guru
            $guru->delete();
            
            // Hapus user terkait
            User::where('id_user', $idUser)->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus guru',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get jadwal mengajar guru.
     */
    public function getJadwal($id)
    {
        $guru = Guru::find($id);
        
        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }
        
        $jadwal = $guru->jadwal()->with(['kelas', 'mataPelajaran'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $jadwal,
        ], 200);
    }
}
