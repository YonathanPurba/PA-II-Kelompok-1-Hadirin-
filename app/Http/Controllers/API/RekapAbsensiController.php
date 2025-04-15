<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RekapAbsensi;
use App\Models\Absensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'nullable|exists:siswa,id_siswa',
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
            'bulan' => 'nullable|numeric|min:1|max:12',
            'tahun' => 'nullable|numeric|min:2000|max:2100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = RekapAbsensi::with(['siswa', 'kelas']);

        if ($request->has('id_siswa')) {
            $query->where('id_siswa', $request->id_siswa);
        }

        if ($request->has('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->has('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->has('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $rekap = $query->get();

        return response()->json([
            'success' => true,
            'data' => $rekap
        ]);
    }

    public function generateRekap(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000|max:2100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $id_kelas = $request->id_kelas;

        // Get all students in the class
        $siswa = Siswa::where('id_kelas', $id_kelas)->get();

        DB::beginTransaction();
        try {
            foreach ($siswa as $s) {
                // Count attendance for each student
                $hadir = Absensi::where('id_siswa', $s->id_siswa)
                                ->whereMonth('tanggal', $bulan)
                                ->whereYear('tanggal', $tahun)
                                ->where('status', 'hadir')
                                ->count();
                
                $sakit = Absensi::where('id_siswa', $s->id_siswa)
                                ->whereMonth('tanggal', $bulan)
                                ->whereYear('tanggal', $tahun)
                                ->whereMonth('tanggal', $bulan)
                                ->whereYear('tanggal', $tahun)
                                ->where('status', 'sakit')
                                ->count();
                
                $izin = Absensi::where('id_siswa', $s->id_siswa)
                               ->whereMonth('tanggal', $bulan)
                               ->whereYear('tanggal', $tahun)
                               ->where('status', 'izin')
                               ->count();
                
                $alpa = Absensi::where('id_siswa', $s->id_siswa)
                               ->whereMonth('tanggal', $bulan)
                               ->whereYear('tanggal', $tahun)
                               ->where('status', 'alpa')
                               ->count();
                
                // Create or update rekap
                RekapAbsensi::updateOrCreate(
                    [
                        'id_siswa' => $s->id_siswa,
                        'id_kelas' => $id_kelas,
                        'bulan' => $bulan,
                        'tahun' => $tahun
                    ],
                    [
                        'jumlah_hadir' => $hadir,
                        'jumlah_sakit' => $sakit,
                        'jumlah_izin' => $izin,
                        'jumlah_alpa' => $alpa,
                        'dibuat_oleh' => $request->user()->username,
                        'diperbarui_oleh' => $request->user()->username
                    ]
                );
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Rekap absensi berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat rekap absensi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $rekap = RekapAbsensi::with(['siswa', 'kelas'])
                            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $rekap
        ]);
    }

    public function getByClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000|max:2100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $rekap = RekapAbsensi::with(['siswa'])
                            ->where('id_kelas', $request->id_kelas)
                            ->where('bulan', $request->bulan)
                            ->where('tahun', $request->tahun)
                            ->get();

        return response()->json([
            'success' => true,
            'data' => $rekap
        ]);
    }

    public function getByStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'tahun' => 'nullable|numeric|min:2000|max:2100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = RekapAbsensi::with(['kelas'])
                            ->where('id_siswa', $request->id_siswa);

        if ($request->has('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $rekap = $query->orderBy('tahun', 'desc')
                       ->orderBy('bulan', 'desc')
                       ->get();

        return response()->json([
            'success' => true,
            'data' => $rekap
        ]);
    }
}