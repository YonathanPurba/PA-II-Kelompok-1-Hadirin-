<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrangTuaController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::withCount('siswa')->with('guru')->get();

        return view('admin.pages.orang_tua.data-orang-tua', compact('kelasList'));
    }

    public function showByKelas($id_kelas)
    {
        $kelas = Kelas::with(['guru', 'siswa.orangtua'])->findOrFail($id_kelas);

        return view('admin.pages.orang_tua.detail-orang-tua', compact('kelas'));
    }

    public function show($id)
    {
        $orangTua = OrangTua::with(['user', 'siswa'])->find($id);

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|exists:users,id_user',
            'nama_lengkap' => 'required|string|max:255',
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

        $orangTua = OrangTua::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Orang tua berhasil ditambahkan',
            'data' => $orangTua,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $orangTua = OrangTua::find($id);

        if (!$orangTua) {
            return response()->json([
                'success' => false,
                'message' => 'Orang tua tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_user' => 'exists:users,id_user',
            'nama_lengkap' => 'string|max:255',
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

        $orangTua->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Orang tua berhasil diperbarui',
            'data' => $orangTua,
        ], 200);
    }

    public function destroy($id)
    {
        $orangTua = OrangTua::find($id);

        if (!$orangTua) {
            return response()->json([
                'success' => false,
                'message' => 'Orang tua tidak ditemukan',
            ], 404);
        }

        $orangTua->delete();

        return response()->json([
            'success' => true,
            'message' => 'Orang tua berhasil dihapus',
        ], 200);
    }
}
