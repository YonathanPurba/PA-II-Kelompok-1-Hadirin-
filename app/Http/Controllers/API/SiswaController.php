<?php

namespace App\Http\API;

use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::with(['orangtua', 'kelas'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:siswa,nis',
            'id_orangtua' => 'required|exists:orangtua,id_orangtua',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $siswa = Siswa::create([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'id_orangtua' => $request->id_orangtua,
                'id_kelas' => $request->id_kelas,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            // Load relasi untuk response
            $siswa->load(['orangtua', 'kelas']);

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan',
                'data' => $siswa,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan siswa',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $siswa = Siswa::with(['orangtua', 'kelas'])->find($id);
        
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::find($id);
        
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nama' => 'string|max:255',
            'nis' => 'string|max:20|unique:siswa,nis,' . $id . ',id_siswa',
            'id_orangtua' => 'exists:orangtua,id_orangtua',
            'id_kelas' => 'exists:kelas,id_kelas',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'in:laki-laki,perempuan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $siswaData = $request->only([
                'nama', 'nis', 'id_orangtua', 'id_kelas', 'tanggal_lahir', 'jenis_kelamin'
            ]);
            
            $siswaData['diperbarui_pada'] = now();
            $siswaData['diperbarui_oleh'] = 'API';
            
            $siswa->update($siswaData);
            
            // Refresh model untuk mendapatkan data terbaru
            $siswa->load(['orangtua', 'kelas']);

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil diperbarui',
                'data' => $siswa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui siswa',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $siswa = Siswa::find($id);
        
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan',
            ], 404);
        }
        
        try {
            $siswa->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus siswa',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get siswa by kelas.
     */
    public function getByKelas($kelasId)
    {
        $siswa = Siswa::with(['orangtua'])
            ->where('id_kelas', $kelasId)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }

    /**
     * Get siswa by orang tua.
     */
    public function getByOrangTua($orangTuaId)
    {
        $siswa = Siswa::with(['kelas'])
            ->where('id_orangtua', $orangTuaId)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }
}
