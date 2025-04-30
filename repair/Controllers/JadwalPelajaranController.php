<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Validator;

class JadwalPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $jadwal = Jadwal::with(['mataPelajaran', 'guru', 'kelas']);

        if ($request->filled('id_kelas')) {
            $jadwal->where('id_kelas', $request->id_kelas);
        }

        $jadwal = $jadwal->orderByRaw("
            FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat')
        ")
            ->orderBy('waktu_mulai')
            ->get();

        return view('admin.pages.jadwal_pelajaran.manajemen_data_jadwal_pelajaran', [
            'kelas' => $kelas,
            'jadwal' => $jadwal
        ]);
    }


    // // Ambil semua kelas
    // $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

    // // Ambil semua mata pelajaran
    // $mataPelajaranList = MataPelajaran::orderBy('nama')->get();

    // // Ambil semua guru dari user yang memiliki role guru
    // $guruList = Guru::orderBy('nama_lengkap')->get();

    // return view('admin.pages.jadwal_pelajaran.manajemen_data_jadwal_pelajaran', compact('kelas', 'mataPelajaranList', 'guruList'));


    public function show($id)
    {
        $jadwalPelajaran = Jadwal::with(['user', 'kelas', 'mataPelajaran', 'guru', 'absensi'])->find($id);

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

        $jadwalPelajaran = Jadwal::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal pelajaran berhasil ditambahkan',
            'data' => $jadwalPelajaran,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $jadwalPelajaran = Jadwal::find($id);

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
        $jadwalPelajaran = Jadwal::find($id);

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
        $jadwalPelajaran = Jadwal::with(['mataPelajaran', 'guru'])
            ->where('kelas_id', $kelasId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jadwalPelajaran,
        ], 200);
    }

    public function getByGuru($guruId)
    {
        $jadwalPelajaran = Jadwal::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guruId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jadwalPelajaran,
        ], 200);
    }
}
