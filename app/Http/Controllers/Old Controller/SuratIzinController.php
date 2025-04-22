<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SuratIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuratIzinController extends Controller
{
    public function index()
    {
        $suratIzin = SuratIzin::with(['siswa', 'guru'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $suratIzin,
        ], 200);
    }

    public function show($id)
    {
        $suratIzin = SuratIzin::with(['siswa', 'guru', 'absensi'])->find($id);
        
        if (!$suratIzin) {
            return response()->json([
                'success' => false,
                'message' => 'Surat izin tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $suratIzin,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'tanggal' => 'required|date',
            'isi_surat' => 'required|string',
            'status' => 'required|in:disetujui,ditolak',
            'id_guru' => 'nullable|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $suratIzin = SuratIzin::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Surat izin berhasil ditambahkan',
            'data' => $suratIzin,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $suratIzin = SuratIzin::find($id);
        
        if (!$suratIzin) {
            return response()->json([
                'success' => false,
                'message' => 'Surat izin tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'exists:siswa,id_siswa',
            'tanggal' => 'date',
            'isi_surat' => 'string',
            'status' => 'in:disetujui,ditolak',
            'id_guru' => 'nullable|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $suratIzin->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Surat izin berhasil diperbarui',
            'data' => $suratIzin,
        ], 200);
    }

    public function destroy($id)
    {
        $suratIzin = SuratIzin::find($id);
        
        if (!$suratIzin) {
            return response()->json([
                'success' => false,
                'message' => 'Surat izin tidak ditemukan',
            ], 404);
        }
        
        $suratIzin->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Surat izin berhasil dihapus',
        ], 200);
    }

    public function getBySiswa($siswaId)
    {
        $suratIzin = SuratIzin::with('guru')
            ->where('id_siswa', $siswaId)
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $suratIzin,
        ], 200);
    }

    public function getByGuru($guruId)
    {
        $suratIzin = SuratIzin::with('siswa')
            ->where('id_guru', $guruId)
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $suratIzin,
        ], 200);
    }

    public function approve(Request $request, $id)
    {
        $suratIzin = SuratIzin::find($id);
        
        if (!$suratIzin) {
            return response()->json([
                'success' => false,
                'message' => 'Surat izin tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'id_guru' => 'required|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $suratIzin->status = 'disetujui';
        $suratIzin->id_guru = $request->id_guru;
        $suratIzin->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Surat izin berhasil disetujui',
            'data' => $suratIzin,
        ], 200);
    }

    public function reject(Request $request, $id)
    {
        $suratIzin = SuratIzin::find($id);
        
        if (!$suratIzin) {
            return response()->json([
                'success' => false,
                'message' => 'Surat izin tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'id_guru' => 'required|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $suratIzin->status = 'ditolak';
        $suratIzin->id_guru = $request->id_guru;
        $suratIzin->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Surat izin berhasil ditolak',
            'data' => $suratIzin,
        ], 200);
    }
}