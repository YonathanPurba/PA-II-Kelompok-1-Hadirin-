<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mataPelajaran = MataPelajaran::with('user')->get();
        
        return response()->json([
            'success' => true,
            'data' => $mataPelajaran,
        ], 200);
    }

    public function show($id)
    {
        $mataPelajaran = MataPelajaran::with(['user', 'guru', 'jadwalPelajaran'])->find($id);
        
        if (!$mataPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $mataPelajaran,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_mata_pelajaran' => 'required|string|max:255',
            'deskripsi_mata_pelajaran' => 'nullable|string',
            'id_user' => 'nullable|exists:users,id_user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $mataPelajaran = MataPelajaran::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil ditambahkan',
            'data' => $mataPelajaran,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $mataPelajaran = MataPelajaran::find($id);
        
        if (!$mataPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nama_mata_pelajaran' => 'string|max:255',
            'deskripsi_mata_pelajaran' => 'nullable|string',
            'id_user' => 'nullable|exists:users,id_user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $mataPelajaran->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil diperbarui',
            'data' => $mataPelajaran,
        ], 200);
    }

    public function destroy($id)
    {
        $mataPelajaran = MataPelajaran::find($id);
        
        if (!$mataPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran tidak ditemukan',
            ], 404);
        }
        
        $mataPelajaran->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil dihapus',
        ], 200);
    }
}