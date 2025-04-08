<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('user')->get();
        
        return response()->json([
            'success' => true,
            'data' => $kelas,
        ], 200);
    }

    public function show($id)
    {
        $kelas = Kelas::with(['user', 'siswa', 'jadwalPelajaran'])->find($id);
        
        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $kelas,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|string|max:50',
            'tingkat_kelas' => 'required|string|max:20',
            'tahun_ajaran' => 'required|string|max:10',
            'id_user' => 'nullable|exists:users,id_user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $kelas = Kelas::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil ditambahkan',
            'data' => $kelas,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::find($id);
        
        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'string|max:50',
            'tingkat_kelas' => 'string|max:20',
            'tahun_ajaran' => 'string|max:10',
            'id_user' => 'nullable|exists:users,id_user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $kelas->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diperbarui',
            'data' => $kelas,
        ], 200);
    }

    public function destroy($id)
    {
        $kelas = Kelas::find($id);
        
        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan',
            ], 404);
        }
        
        $kelas->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus',
        ], 200);
    }
}