<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrangTuaController extends Controller
{
    public function index(Request $request)
    {
        $kelasId = $request->input('kelas');

        $kelasList = Kelas::all();

        $orangTuaList = OrangTua::with(['siswa.kelas', 'user'])
            ->when($kelasId, function ($query) use ($kelasId) {
                $query->whereHas('siswa', function ($siswaQuery) use ($kelasId) {
                    $siswaQuery->where('id_kelas', $kelasId);
                });
            })
            ->get();

        // Jika ada filter kelas, kita filter ulang anak-anak yang bukan dari kelas itu
        if ($kelasId) {
            foreach ($orangTuaList as $orangTua) {
                $orangTua->setRelation('siswa', $orangTua->siswa->where('id_kelas', $kelasId));
            }
        }

        return view('admin.pages.orang_tua.manajemen_data_orang_tua', compact('orangTuaList', 'kelasList'));
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

    public function edit($id)
    {
        $orangTua = OrangTua::with('user')->findOrFail($id);

        return view('admin.pages.orang_tua.edit_orang_tua', compact('orangTua'));
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
