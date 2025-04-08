<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')->get(); // Jika relasi dengan user diperlukan

        return view('admin.pages.guru.manajemen_data_guru', compact('gurus'));
    }
    
    public function show($id)
    {
        $guru = Guru::with(['user', 'mataPelajaran', 'jadwalPelajaran'])->find($id);
        
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|max:20|unique:guru',
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'foto_profil' => 'nullable|string|max:255',
            'id_user' => 'required|exists:users,id_user',
            'id_mata_pelajaran' => 'nullable|exists:mata_pelajaran,id_mata_pelajaran',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $guru = Guru::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil ditambahkan',
            'data' => $guru,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::find($id);
        
        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nip' => 'string|max:20|unique:guru,nip,' . $id . ',id_guru',
            'nama' => 'string|max:255',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'in:Laki-laki,Perempuan',
            'foto_profil' => 'nullable|string|max:255',
            'id_user' => 'exists:users,id_user',
            'id_mata_pelajaran' => 'nullable|exists:mata_pelajaran,id_mata_pelajaran',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $guru->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil diperbarui',
            'data' => $guru,
        ], 200);
    }

    public function destroy($id)
    {
        $guru = Guru::find($id);
        
        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }
        
        $guru->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil dihapus',
        ], 200);
    }
}