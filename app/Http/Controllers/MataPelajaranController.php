<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Models\GuruMataPelajaran;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MataPelajaranController extends Controller
{
    public function index()
    {
        // Mengambil semua mata pelajaran
        $mataPelajaran = MataPelajaran::all();

        // Menghitung jumlah guru yang mengajar tiap mata pelajaran
        foreach ($mataPelajaran as $mapel) {
            // Menghitung jumlah guru berdasarkan id_mata_pelajaran
            $mapel->jumlah_guru = GuruMataPelajaran::where('id_mata_pelajaran', $mapel->id_mata_pelajaran)->count();
        }

        // Mengirim data mata pelajaran beserta jumlah guru ke view
        return view('admin.pages.mata_pelajaran.manajemen_data_mata_pelajaran', compact('mataPelajaran'));
    }

    public function getJumlahGuru($id)
    {
        $jumlahGuru = GuruMataPelajaran::where('id_mata_pelajaran', $id)->count();

        return response()->json([
            'jumlah_guru' => $jumlahGuru
        ]);
    }

    public function getGuruPengampu($id)
    {
        $guruIDs = GuruMataPelajaran::where('id_mata_pelajaran', $id)->pluck('id_guru');
        $guruList = Guru::whereIn('id_guru', $guruIDs)->get(['id_guru', 'nama']);

        return response()->json([
            'jumlah' => $guruList->count(),
            'data' => $guruList,
        ]);
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
