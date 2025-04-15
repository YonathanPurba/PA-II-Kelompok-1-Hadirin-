<?php

namespace App\Http\API;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absensi = Absensi::with(['siswa', 'jadwal'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $absensi,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpa',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek apakah absensi sudah ada
        $existingAbsensi = Absensi::where('id_siswa', $request->id_siswa)
            ->where('id_jadwal', $request->id_jadwal)
            ->where('tanggal', $request->tanggal)
            ->first();
            
        if ($existingAbsensi) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi untuk siswa, jadwal, dan tanggal ini sudah ada',
            ], 409);
        }

        try {
            $absensi = Absensi::create([
                'id_siswa' => $request->id_siswa,
                'id_jadwal' => $request->id_jadwal,
                'tanggal' => $request->tanggal,
                'status' => $request->status,
                'catatan' => $request->catatan,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            // Load relasi untuk response
            $absensi->load(['siswa', 'jadwal']);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil ditambahkan',
                'data' => $absensi,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan absensi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $absensi = Absensi::with(['siswa', 'jadwal'])->find($id);
        
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

    /**
     * Update the specified resource in storage.
     */
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
            'id_siswa' => 'exists:siswa,id_siswa',
            'id_jadwal' => 'exists:jadwal,id_jadwal',
            'tanggal' => 'date',
            'status' => 'in:hadir,izin,sakit,alpa',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $absensiData = $request->only([
                'id_siswa', 'id_jadwal', 'tanggal', 'status', 'catatan'
            ]);
            
            $absensiData['diperbarui_pada'] = now();
            $absensiData['diperbarui_oleh'] = 'API';
            
            $absensi->update($absensiData);
            
            // Refresh model untuk mendapatkan data terbaru
            $absensi->load(['siswa', 'jadwal']);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil diperbarui',
                'data' => $absensi,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui absensi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $absensi = Absensi::find($id);
        
        if (!$absensi) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi tidak ditemukan',
            ], 404);
        }
        
        try {
            $absensi->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus absensi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get absensi by siswa.
     */
    public function getBySiswa($siswaId)
    {
        $absensi = Absensi::with(['jadwal'])
            ->where('id_siswa', $siswaId)
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $absensi,
        ], 200);
    }

    /**
     * Get absensi by jadwal.
     */
    public function getByJadwal($jadwalId, $tanggal = null)
    {
        $query = Absensi::with(['siswa'])
            ->where('id_jadwal', $jadwalId);
            
        if ($tanggal) {
            $query->where('tanggal', $tanggal);
        }
        
        $absensi = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $absensi,
        ], 200);
    }

    /**
     * Create bulk absensi.
     */
    public function createBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'absensi' => 'required|array',
            'absensi.*.id_siswa' => 'required|exists:siswa,id_siswa',
            'absensi.*.status' => 'required|in:hadir,izin,sakit,alpa',
            'absensi.*.catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $tanggal = $request->tanggal;
            $idJadwal = $request->id_jadwal;
            $absensiData = $request->absensi;
            
            $createdAbsensi = [];
            
            foreach ($absensiData as $data) {
                // Cek apakah absensi sudah ada
                $existingAbsensi = Absensi::where('id_siswa', $data['id_siswa'])
                    ->where('id_jadwal', $idJadwal)
                    ->where('tanggal', $tanggal)
                    ->first();
                    
                if ($existingAbsensi) {
                    // Update absensi yang sudah ada
                    $existingAbsensi->update([
                        'status' => $data['status'],
                        'catatan' => $data['catatan'] ?? null,
                        'diperbarui_pada' => now(),
                        'diperbarui_oleh' => 'API',
                    ]);
                    
                    $createdAbsensi[] = $existingAbsensi;
                } else {
                    // Buat absensi baru
                    $absensi = Absensi::create([
                        'tanggal' => $tanggal,
                        'id_jadwal' => $idJadwal,
                        'id_siswa' => $data['id_siswa'],
                        'status' => $data['status'],
                        'catatan' => $data['catatan'] ?? null,
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'API',
                    ]);
                    
                    $createdAbsensi[] = $absensi;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil ditambahkan',
                'data' => $createdAbsensi,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan absensi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
