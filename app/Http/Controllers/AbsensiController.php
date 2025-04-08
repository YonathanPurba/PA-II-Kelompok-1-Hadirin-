<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensi = Absensi::with(['user', 'jadwalPelajaran', 'siswa', 'suratIzin'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $absensi,
        ], 200);
    }

    public function show($id)
    {
        $absensi = Absensi::with(['user', 'jadwalPelajaran', 'siswa', 'suratIzin'])->find($id);
        
        if (!$absensi) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $absensi,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_absensi' => 'required|date',
            'id_user' => 'nullable|exists:users,id_user',
            'id_jadwal' => 'required|exists:jadwal_pelajaran,id_jadwal',
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_surat_izin' => 'nullable|exists:surat_izin,id_surat_izin',
            'status_absensi' => 'required|in:hadir,izin,sakit,alpa',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $absensi = Absensi::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil ditambahkan',
            'data' => $absensi,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $absensi = Absensi::find($id);
        
        if (!$absensi) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'tanggal_absensi' => 'date',
            'id_user' => 'nullable|exists:users,id_user',
            'id_jadwal' => 'exists:jadwal_pelajaran,id_jadwal',
            'id_siswa' => 'exists:siswa,id_siswa',
            'id_surat_izin' => 'nullable|exists:surat_izin,id_surat_izin',
            'status_absensi' => 'in:hadir,izin,sakit,alpa',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $absensi->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil diperbarui',
            'data' => $absensi,
        ], 200);
    }

    public function destroy($id)
    {
        $absensi = Absensi::find($id);
        
        if (!$absensi) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi tidak ditemukan',
            ], 404);
        }
        
        $absensi->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil dihapus',
        ], 200);
    }

    public function getBySiswa($siswaId)
    {
        $absensi = Absensi::with(['jadwalPelajaran', 'suratIzin'])
            ->where('id_siswa', $siswaId)
            ->orderBy('tanggal_absensi', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $absensi,
        ], 200);
    }

    public function getByJadwal($jadwalId, $tanggal = null)
    {
        $query = Absensi::with(['siswa', 'suratIzin'])
            ->where('id_jadwal', $jadwalId);
            
        if ($tanggal) {
            $query->where('tanggal_absensi', $tanggal);
        }
        
        $absensi = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $absensi,
        ], 200);
    }

    public function createBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_absensi' => 'required|date',
            'id_jadwal' => 'required|exists:jadwal_pelajaran,id_jadwal',
            'id_user' => 'required|exists:users,id_user',
            'absensi' => 'required|array',
            'absensi.*.id_siswa' => 'required|exists:siswa,id_siswa',
            'absensi.*.status_absensi' => 'required|in:hadir,izin,sakit,alpa',
            'absensi.*.id_surat_izin' => 'nullable|exists:surat_izin,id_surat_izin',
            'absensi.*.catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $tanggalAbsensi = $request->tanggal_absensi;
        $idJadwal = $request->id_jadwal;
        $idUser = $request->id_user;
        $absensiData = $request->absensi;
        
        $createdAbsensi = [];
        
        foreach ($absensiData as $data) {
            $absensi = Absensi::create([
                'tanggal_absensi' => $tanggalAbsensi,
                'id_jadwal' => $idJadwal,
                'id_user' => $idUser,
                'id_siswa' => $data['id_siswa'],
                'status_absensi' => $data['status_absensi'],
                'id_surat_izin' => $data['id_surat_izin'] ?? null,
                'catatan' => $data['catatan'] ?? null,
            ]);
            
            $createdAbsensi[] = $absensi;
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil ditambahkan',
            'data' => $createdAbsensi,
        ], 201);
    }
}