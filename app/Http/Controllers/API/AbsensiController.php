<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    use ApiResponser;

    /**
     * Get attendance records
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jadwal' => 'nullable|exists:jadwal,id_jadwal',
            'tanggal' => 'nullable|date_format:Y-m-d',
            'id_siswa' => 'nullable|exists:siswa,id_siswa',
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
            'bulan' => 'nullable|numeric|min:1|max:12',
            'tahun' => 'nullable|numeric|min:2000|max:2100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $query = Absensi::with(['siswa', 'jadwal.mataPelajaran', 'jadwal.guru']);

        if ($request->has('id_jadwal')) {
            $query->where('id_jadwal', $request->id_jadwal);
        }

        if ($request->has('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        if ($request->has('id_siswa')) {
            $query->where('id_siswa', $request->id_siswa);
        }

        if ($request->has('id_kelas')) {
            $query->whereHas('jadwal', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        if ($request->has('bulan') && $request->has('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                ->whereYear('tanggal', $request->tahun);
        }

        $perPage = $request->input('per_page', 15);
        $absensi = $query->orderBy('tanggal', 'desc')
            ->paginate($perPage);

        return $this->paginatedResponse($absensi, 'Data absensi berhasil diambil');
    }

    /**
     * Store attendance records
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'tanggal' => 'required|date_format:Y-m-d',
            'absensi' => 'required|array',
            'absensi.*.id_siswa' => 'required|exists:siswa,id_siswa',
            'absensi.*.status' => 'required|in:hadir,alpa,sakit,izin',
            'absensi.*.catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        DB::beginTransaction();
        try {
            $createdRecords = [];

            foreach ($request->absensi as $item) {
                $absensi = Absensi::updateOrCreate(
                    [
                        'id_siswa' => $item['id_siswa'],
                        'id_jadwal' => $request->id_jadwal,
                        'tanggal' => $request->tanggal,
                    ],
                    [
                        'status' => $item['status'],
                        'catatan' => $item['catatan'] ?? null,
                        'dibuat_oleh' => $request->user()->username,
                        'diperbarui_oleh' => $request->user()->username,
                    ]
                );

                $createdRecords[] = $absensi->load('siswa');
            }

            DB::commit();

            return $this->successResponse($createdRecords, 'Absensi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->errorResponse('Gagal menyimpan absensi: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get attendance record by ID
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $absensi = Absensi::with(['siswa', 'jadwal.mataPelajaran', 'jadwal.guru'])
                ->findOrFail($id);

            return $this->successResponse($absensi, 'Data absensi berhasil diambil');
        } catch (\Exception $e) {
            return $this->errorResponse('Data absensi tidak ditemukan', 404);
        }
    }

    /**
     * Update attendance record
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:hadir,alpa,sakit,izin',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        try {
            $absensi = Absensi::findOrFail($id);

            $absensi->status = $request->status;
            $absensi->catatan = $request->catatan;
            $absensi->diperbarui_oleh = $request->user()->username;
            $absensi->save();

            return $this->successResponse($absensi, 'Absensi berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->errorResponse('Data absensi tidak ditemukan', 404);
        }
    }

    /**
     * Get attendance by student
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'bulan' => 'nullable|numeric|min:1|max:12',
            'tahun' => 'nullable|numeric|min:2000|max:2100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $query = Absensi::with(['jadwal.mataPelajaran', 'jadwal.guru'])
            ->where('id_siswa', $request->id_siswa);

        if ($request->has('bulan') && $request->has('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                ->whereYear('tanggal', $request->tahun);
        }

        $perPage = $request->input('per_page', 15);
        $absensi = $query->orderBy('tanggal', 'desc')
            ->paginate($perPage);

        return $this->paginatedResponse($absensi, 'Data absensi siswa berhasil diambil');
    }

    /**
     * Get attendance by class
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'tanggal' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        // Get all jadwal for the class on the specified date
        $hari = strtolower(Carbon::parse($request->tanggal)->locale('id')->dayName);

        $jadwals = Jadwal::where('id_kelas', $request->id_kelas)
            ->where('hari', $hari)
            ->with(['mataPelajaran', 'guru'])
            ->get();

        $result = [];

        foreach ($jadwals as $jadwal) {
            $absensi = Absensi::with(['siswa'])
                ->where('id_jadwal', $jadwal->id_jadwal)
                ->where('tanggal', $request->tanggal)
                ->get();

            $result[] = [
                'jadwal' => $jadwal,
                'absensi' => $absensi
            ];
        }

        return $this->successResponse($result, 'Data absensi kelas berhasil diambil');
    }

    /**
     * Get attendance summary
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000|max:2100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $summary = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpa' => 0,
            'total' => 0
        ];

        $absensi = Absensi::where('id_siswa', $request->id_siswa)
            ->whereMonth('tanggal', $request->bulan)
            ->whereYear('tanggal', $request->tahun)
            ->get();

        foreach ($absensi as $item) {
            $summary[$item->status]++;
            $summary['total']++;
        }

        // Get student info
        $siswa = Siswa::with('kelas')->find($request->id_siswa);

        $result = [
            'siswa' => $siswa,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'summary' => $summary
        ];

        return $this->successResponse($result, 'Ringkasan absensi berhasil diambil');
    }

    /**
     * Get attendance for today
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToday(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
            'id_guru' => 'nullable|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $today = Carbon::today()->format('Y-m-d');
        $hari = strtolower(Carbon::today()->locale('id')->dayName);

        $query = Jadwal::where('hari', $hari);

        if ($request->has('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->has('id_guru')) {
            $query->where('id_guru', $request->id_guru);
        }

        $jadwals = $query->with(['mataPelajaran', 'guru', 'kelas'])
            ->orderBy('waktu_mulai')
            ->get();

        $result = [];

        foreach ($jadwals as $jadwal) {
            $absensi = Absensi::with(['siswa'])
                ->where('id_jadwal', $jadwal->id_jadwal)
                ->where('tanggal', $today)
                ->get();

            $result[] = [
                'jadwal' => $jadwal,
                'absensi' => $absensi,
                'total_siswa' => Siswa::where('id_kelas', $jadwal->id_kelas)->count(),
                'total_absensi' => $absensi->count()
            ];
        }

        return $this->successResponse($result, 'Data absensi hari ini berhasil diambil');
    }



    public function checkAbsensiStatus($idJadwal, $tanggal)
    {
        try {
            // Cek apakah absensi sudah tercatat pada jadwal dan tanggal tertentu
            $absensi = Absensi::where('id_jadwal', $idJadwal)
                ->whereDate('tanggal', $tanggal)
                ->first();

            if (!$absensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Absensi belum tercatat',
                    'data' => ['exists' => false]
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Absensi sudah tercatat',
                'data' => ['exists' => true]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => ['exists' => false]
            ], 500);
        }
    }

    public function getAbsensiData($idJadwal, $tanggal)
    {
        try {
            // Ambil semua data absensi berdasarkan jadwal dan tanggal tertentu
            $absensiData = Absensi::where('id_jadwal', $idJadwal)
                ->whereDate('tanggal', $tanggal)
                ->get();

            if ($absensiData->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data returned',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $absensiData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
