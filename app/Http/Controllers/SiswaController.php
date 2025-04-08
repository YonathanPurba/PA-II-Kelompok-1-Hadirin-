<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with(['user', 'kelas', 'orangTua'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }

    public function show($id)
    {
        $siswa = Siswa::with(['user', 'kelas', 'orangTua', 'absensi'])->find($id);
        
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string|max:10|unique:siswa',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas_id' => 'nullable|exists:kelas,id_kelas',
            'id_user' => 'nullable|exists:users,id_user',
            'id_orang_tua' => 'nullable|exists:orang_tua,id_orang_tua',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $siswa = Siswa::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil ditambahkan',
            'data' => $siswa,
        ], 201);
    }

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
            'nama_lengkap' => 'string|max:255',
            'nisn' => 'string|max:10|unique:siswa,nisn,' . $id . ',id_siswa',
            'jenis_kelamin' => 'in:Laki-laki,Perempuan',
            'kelas_id' => 'nullable|exists:kelas,id_kelas',
            'id_user' => 'nullable|exists:users,id_user',
            'id_orang_tua' => 'nullable|exists:orang_tua,id_orang_tua',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $siswa->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil diperbarui',
            'data' => $siswa,
        ], 200);
    }

    public function destroy($id)
    {
        $siswa = Siswa::find($id);
        
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan',
            ], 404);
        }
        
        $siswa->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dihapus',
        ], 200);
    }

    public function getByKelas($kelasId)
    {
        $siswa = Siswa::with(['user', 'orangTua'])
            ->where('kelas_id', $kelasId)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }

    public function getByOrangTua($orangTuaId)
    {
        $siswa = Siswa::with(['kelas'])
            ->where('id_orang_tua', $orangTuaId)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }
}