<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalPelajaranController extends Controller
{
    public function index()
    {
        $jadwalPelajaran = JadwalPelajaran::with(['user', 'kelas', 'mataPelajaran', 'guru'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $jadwalPelajaran,
        ], 200);
    }

    public function show($id)
    {
        $jadwalPelajaran = JadwalPelajaran::with(['user', 'kelas', 'mataPelajaran', 'guru', 'absensi'])->find($id);
        
        if (!$jadwalPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal pelajaran tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $jadwalPelajaran,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jam' => 'required|integer',
            'kelas_id' => 'required|exists:kelas,id_kelas',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'guru_id' => 'required|exists:guru,id_guru',
            'id_user' => 'nullable|exists:users,id_user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $jadwalPelajaran = JadwalPelajaran::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Jadwal pelajaran berhasil ditambahkan',
            'data' => $jadwalPelajaran,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $jadwalPelajaran = JadwalPelajaran::find($id);
        
        if (!$jadwalPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal pelajaran tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'id_jam' => 'integer',
            'kelas_id' => 'exists:kelas,id_kelas',
            'mata_pelajaran_id' => 'exists:mata_pelajaran,id_mata_pelajaran',
            'guru_id' => 'exists:guru,id_guru',
            'id_user' => 'nullable|exists:users,id_user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $jadwalPelajaran->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Jadwal pelajaran berhasil diperbarui',
            'data' => $jadwalPelajaran,
        ], 200);
    }

    public function destroy($id)
    {
        $jadwalPelajaran = JadwalPelajaran::find($id);
        
        if (!$jadwalPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal pelajaran tidak ditemukan',
            ], 404);
        }
        
        $jadwalPelajaran->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Jadwal pelajaran berhasil dihapus',
        ], 200);
    }

    public function getByKelas($kelasId)
    {
        $jadwalPelajaran = JadwalPelajaran::with(['mataPelajaran', 'guru'])
            ->where('kelas_id', $kelasId)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $jadwalPelajaran,
        ], 200);
    }

    public function getByGuru($guruId)
    {
        $jadwalPelajaran = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guruId)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $jadwalPelajaran,
        ], 200);
    }
}