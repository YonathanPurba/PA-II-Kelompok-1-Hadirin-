<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
            'id_guru' => 'nullable|exists:guru,id_guru',
            'hari' => 'nullable|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'semester' => 'nullable|in:ganjil,genap',
            'id_tahun_ajaran' => 'nullable|exists:tahun_ajaran,id_tahun_ajaran',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Jadwal::with(['kelas', 'mataPelajaran', 'guru', 'tahunAjaran']);

        if ($request->has('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->has('id_guru')) {
            $query->where('id_guru', $request->id_guru);
        }

        if ($request->has('hari')) {
            $query->where('hari', $request->hari);
        }

        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->has('id_tahun_ajaran')) {
            $query->where('id_tahun_ajaran', $request->id_tahun_ajaran);
        }

        $jadwal = $query->orderBy('hari')
                        ->orderBy('waktu_mulai')
                        ->get();

        return response()->json([
            'success' => true,
            'data' => $jadwal
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'id_guru' => 'required|exists:guru,id_guru',
            'id_guru_mata_pelajaran' => 'nullable|exists:guru_mata_pelajaran,id_guru_mata_pelajaran',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'semester' => 'required|in:ganjil,genap',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jadwal = new Jadwal();
        $jadwal->id_kelas = $request->id_kelas;
        $jadwal->id_mata_pelajaran = $request->id_mata_pelajaran;
        $jadwal->id_guru = $request->id_guru;
        $jadwal->id_guru_mata_pelajaran = $request->id_guru_mata_pelajaran;
        $jadwal->hari = $request->hari;
        $jadwal->semester = $request->semester;
        $jadwal->id_tahun_ajaran = $request->id_tahun_ajaran;
        $jadwal->waktu_mulai = $request->waktu_mulai;
        $jadwal->waktu_selesai = $request->waktu_selesai;
        $jadwal->dibuat_oleh = $request->user()->username;
        $jadwal->save();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dibuat',
            'data' => $jadwal
        ], 201);
    }

    public function show($id)
    {
        $jadwal = Jadwal::with(['kelas', 'mataPelajaran', 'guru', 'tahunAjaran'])
                        ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $jadwal
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
            'id_mata_pelajaran' => 'nullable|exists:mata_pelajaran,id_mata_pelajaran',
            'id_guru' => 'nullable|exists:guru,id_guru',
            'id_guru_mata_pelajaran' => 'nullable|exists:guru_mata_pelajaran,id_guru_mata_pelajaran',
            'hari' => 'nullable|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'semester' => 'nullable|in:ganjil,genap',
            'id_tahun_ajaran' => 'nullable|exists:tahun_ajaran,id_tahun_ajaran',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jadwal = Jadwal::findOrFail($id);

        if ($request->has('id_kelas')) {
            $jadwal->id_kelas = $request->id_kelas;
        }
        
        if ($request->has('id_mata_pelajaran')) {
            $jadwal->id_mata_pelajaran = $request->id_mata_pelajaran;
        }
        
        if ($request->has('id_guru')) {
            $jadwal->id_guru = $request->id_guru;
        }
        
        if ($request->has('id_guru_mata_pelajaran')) {
            $jadwal->id_guru_mata_pelajaran = $request->id_guru_mata_pelajaran;
        }
        
        if ($request->has('hari')) {
            $jadwal->hari = $request->hari;
        }
        
        if ($request->has('semester')) {
            $jadwal->semester = $request->semester;
        }
        
        if ($request->has('id_tahun_ajaran')) {
            $jadwal->id_tahun_ajaran = $request->id_tahun_ajaran;
        }
        
        if ($request->has('waktu_mulai')) {
            $jadwal->waktu_mulai = $request->waktu_mulai;
        }
        
        if ($request->has('waktu_selesai')) {
            $jadwal->waktu_selesai = $request->waktu_selesai;
        }
        
        $jadwal->diperbarui_oleh = $request->user()->username;
        $jadwal->save();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $jadwal
        ]);
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }

    public function getByTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_guru' => 'required|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jadwal = Jadwal::with(['kelas', 'mataPelajaran', 'tahunAjaran'])
                        ->where('id_guru', $request->id_guru)
                        ->orderBy('hari')
                        ->orderBy('waktu_mulai')
                        ->get();

        // Group by day
        $groupedJadwal = $jadwal->groupBy('hari');
        $result = [];

        foreach ($groupedJadwal as $hari => $items) {
            $result[] = [
                'hari' => $hari,
                'jadwal' => $items
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getByClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jadwal = Jadwal::with(['mataPelajaran', 'guru'])
                        ->where('id_kelas', $request->id_kelas)
                        ->orderBy('hari')
                        ->orderBy('waktu_mulai')
                        ->get();

        // Group by day
        $groupedJadwal = $jadwal->groupBy('hari');
        $result = [];

        foreach ($groupedJadwal as $hari => $items) {
            $result[] = [
                'hari' => $hari,
                'jadwal' => $items
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getTodaySchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_guru' => 'nullable|exists:guru,id_guru',
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get today's day name in Indonesian
        $today = strtolower(Carbon::now()->locale('id')->dayName);

        $query = Jadwal::with(['kelas', 'mataPelajaran', 'guru'])
                       ->where('hari', $today);

        if ($request->has('id_guru')) {
            $query->where('id_guru', $request->id_guru);
        }

        if ($request->has('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        $jadwal = $query->orderBy('waktu_mulai')->get();

        return response()->json([
            'success' => true,
            'data' => $jadwal
        ]);
    }

    /**
     * Get schedules by academic year
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByAcademicYear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jadwal = Jadwal::with(['kelas', 'mataPelajaran', 'guru'])
                        ->where('id_tahun_ajaran', $request->id_tahun_ajaran)
                        ->orderBy('hari')
                        ->orderBy('waktu_mulai')
                        ->get();

        // Group by day
        $groupedJadwal = $jadwal->groupBy('hari');
        $result = [];

        foreach ($groupedJadwal as $hari => $items) {
            $result[] = [
                'hari' => $hari,
                'jadwal' => $items
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}
