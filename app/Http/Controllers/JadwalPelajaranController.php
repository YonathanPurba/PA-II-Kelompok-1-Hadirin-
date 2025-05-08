<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JadwalPelajaranController extends Controller
{
    // Define constants for scheduling
    const START_TIME = '08:00';
    const SESSION_DURATION = 45; // in minutes
    const SESSIONS_PER_DAY = 8;
    const SCHOOL_DAYS = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
    const MAX_TEACHER_SESSIONS = 48; // 8 sessions x 6 days

    /**
     * Get all available time slots for scheduling
     */
    private function getTimeSlots()
    {
        $slots = [];
        $startTime = self::START_TIME;
        
        for ($i = 1; $i <= self::SESSIONS_PER_DAY; $i++) {
            $start = date('H:i', strtotime($startTime));
            $end = date('H:i', strtotime("+".self::SESSION_DURATION." minutes", strtotime($start)));
            
            $slots[] = [
                'session' => $i,
                'start' => $start,
                'end' => $end,
                'label' => "Sesi {$i}: {$start} - {$end}"
            ];
            
            $startTime = $end;
        }
        
        return $slots;
    }

    /**
     * Get teacher's current session count
     */
    private function getTeacherSessionCount($teacherId, $excludeJadwalId = null)
    {
        $query = Jadwal::where('id_guru', $teacherId)
            ->where('status', 'aktif');
            
        if ($excludeJadwalId) {
            $query->where('id_jadwal', '!=', $excludeJadwalId);
        }
        
        return $query->count();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $tahunAjaranList = TahunAjaran::orderBy('aktif', 'desc')->orderBy('nama_tahun_ajaran', 'desc')->get();
        $jadwalQuery = Jadwal::with(['mataPelajaran', 'guru', 'kelas', 'tahunAjaran']);

        // Filter berdasarkan kelas
        if ($request->filled('id_kelas')) {
            $jadwalQuery->where('id_kelas', $request->id_kelas);
        }

        // Filter berdasarkan hari
        if ($request->filled('hari')) {
            $jadwalQuery->where('hari', $request->hari);
        }
        
        // Filter berdasarkan tahun ajaran
        if ($request->filled('id_tahun_ajaran')) {
            $jadwalQuery->where('id_tahun_ajaran', $request->id_tahun_ajaran);
        } else {
            // Default tampilkan jadwal tahun ajaran aktif
            $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
            if ($tahunAjaranAktif) {
                $jadwalQuery->where('id_tahun_ajaran', $tahunAjaranAktif->id_tahun_ajaran);
            }
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $jadwalQuery->where('status', $request->status);
        }

        $jadwal = $jadwalQuery->orderByRaw("
            FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')
        ")
            ->orderBy('waktu_mulai')
            ->get();

        return view('admin.pages.jadwal_pelajaran.manajemen_data_jadwal_pelajaran', [
            'kelas' => $kelas,
            'jadwal' => $jadwal,
            'tahunAjaranList' => $tahunAjaranList
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua kelas yang aktif
        $kelas = Kelas::with('tahunAjaran')
            ->whereHas('tahunAjaran', function($query) {
                $query->where('aktif', true);
            })
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();
        
        // Ambil semua mata pelajaran
        $mataPelajaran = MataPelajaran::orderBy('nama')->get();
        
        // Ambil semua guru yang aktif
        $guru = Guru::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
        
        // Ambil jadwal yang sudah ada untuk pengecekan bentrok
        $jadwalExisting = Jadwal::with(['kelas', 'mataPelajaran', 'guru'])
            ->where('status', 'aktif')
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->orderBy('waktu_mulai')
            ->get();
            
        // Get time slots
        $timeSlots = $this->getTimeSlots();
        
        // Get available days
        $schoolDays = self::SCHOOL_DAYS;
        
        return view('admin.pages.jadwal_pelajaran.tambah_jadwal', compact(
            'kelas', 
            'mataPelajaran', 
            'guru', 
            'jadwalExisting', 
            'tahunAjaranAktif',
            'timeSlots',
            'schoolDays'
        ));
    }

    /**
     * Show form for creating multiple schedules at once.
     */
    public function createBulk()
    {
        // Ambil semua kelas yang aktif
        $kelas = Kelas::with('tahunAjaran')
            ->whereHas('tahunAjaran', function($query) {
                $query->where('aktif', true);
            })
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();
        
        // Ambil semua mata pelajaran
        $mataPelajaran = MataPelajaran::orderBy('nama')->get();
        
        // Ambil semua guru yang aktif
        $guru = Guru::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        
        // Ambil tahun ajaran aktif dan semua tahun ajaran
        $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
        $tahunAjaranList = TahunAjaran::orderBy('aktif', 'desc')->orderBy('nama_tahun_ajaran', 'desc')->get();
        
        // Get time slots
        $timeSlots = $this->getTimeSlots();
        
        // Get available days
        $schoolDays = self::SCHOOL_DAYS;
        
        return view('admin.pages.jadwal_pelajaran.tambah_jadwal_massal', compact(
            'kelas', 
            'mataPelajaran', 
            'guru', 
            'tahunAjaranAktif', 
            'tahunAjaranList',
            'timeSlots',
            'schoolDays'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'id_guru' => 'required|exists:guru,id_guru',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'session' => 'required|integer|min:1|max:' . self::SESSIONS_PER_DAY,
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'id_kelas.required' => 'Kelas harus dipilih',
            'id_mata_pelajaran.required' => 'Mata pelajaran harus dipilih',
            'id_guru.required' => 'Guru harus dipilih',
            'id_tahun_ajaran.required' => 'Tahun ajaran harus dipilih',
            'hari.required' => 'Hari harus dipilih',
            'session.required' => 'Sesi harus dipilih',
            'status.required' => 'Status harus dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Get time slot based on session
        $timeSlots = $this->getTimeSlots();
        $selectedSession = $request->session - 1; // Array is 0-indexed
        
        if (!isset($timeSlots[$selectedSession])) {
            return redirect()->back()
                ->with('error', 'Sesi waktu tidak valid')
                ->withInput();
        }
        
        $waktuMulai = $timeSlots[$selectedSession]['start'];
        $waktuSelesai = $timeSlots[$selectedSession]['end'];

        // Check teacher session count
        $teacherSessionCount = $this->getTeacherSessionCount($request->id_guru);
        if ($teacherSessionCount >= self::MAX_TEACHER_SESSIONS) {
            return redirect()->back()
                ->with('error', 'Guru sudah mencapai batas maksimum ' . self::MAX_TEACHER_SESSIONS . ' sesi')
                ->withInput();
        }

        // Cek bentrok jadwal untuk guru
        $bentrokGuru = $this->cekBentrokJadwalGuru(
            $request->id_guru,
            $request->hari,
            $waktuMulai,
            $waktuSelesai
        );

        if ($bentrokGuru) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok! Guru sudah mengajar di kelas lain pada waktu yang sama.')
                ->withInput();
        }

        // Cek bentrok jadwal untuk kelas
        $bentrokKelas = $this->cekBentrokJadwalKelas(
            $request->id_kelas,
            $request->hari,
            $waktuMulai,
            $waktuSelesai
        );

        if ($bentrokKelas) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok! Kelas sudah memiliki jadwal lain pada waktu yang sama.')
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            Jadwal::create([
                'id_kelas' => $request->id_kelas,
                'id_mata_pelajaran' => $request->id_mata_pelajaran,
                'id_guru' => $request->id_guru,
                'id_tahun_ajaran' => $request->id_tahun_ajaran,
                'hari' => $request->hari,
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'status' => $request->status,
                'dibuat_pada' => now(),
                'dibuat_oleh' => Auth::user()->username ?? 'system',
            ]);
            
            DB::commit();
            
            return redirect()->route('jadwal-pelajaran.index')
                ->with('success', 'Jadwal pelajaran berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store multiple schedules at once.
     */
    public function storeBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'jadwal' => 'required|array',
            'jadwal.*.id_kelas' => 'required|exists:kelas,id_kelas',
            'jadwal.*.id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'jadwal.*.id_guru' => 'required|exists:guru,id_guru',
            'jadwal.*.hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jadwal.*.session' => 'required|integer|min:1|max:' . self::SESSIONS_PER_DAY,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $bentrokMessages = [];
        $jadwalData = $request->jadwal;
        $timeSlots = $this->getTimeSlots();
        
        // Check teacher session counts
        $teacherCounts = [];
        foreach ($jadwalData as $jadwal) {
            $teacherId = $jadwal['id_guru'];
            if (!isset($teacherCounts[$teacherId])) {
                $teacherCounts[$teacherId] = $this->getTeacherSessionCount($teacherId);
            }
            $teacherCounts[$teacherId]++;
            
            if ($teacherCounts[$teacherId] > self::MAX_TEACHER_SESSIONS) {
                $guru = Guru::find($teacherId);
                return response()->json([
                    'success' => false,
                    'message' => 'Guru ' . ($guru ? $guru->nama_lengkap : 'Unknown') . ' melebihi batas maksimum ' . self::MAX_TEACHER_SESSIONS . ' sesi',
                ], 422);
            }
        }
        
        // Cek bentrok untuk semua jadwal
        foreach ($jadwalData as $index => $jadwal) {
            // Get time slot based on session
            $selectedSession = $jadwal['session'] - 1; // Array is 0-indexed
            
            if (!isset($timeSlots[$selectedSession])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi waktu tidak valid untuk jadwal ke-' . ($index + 1),
                ], 422);
            }
            
            $waktuMulai = $timeSlots[$selectedSession]['start'];
            $waktuSelesai = $timeSlots[$selectedSession]['end'];
            
            // Cek bentrok jadwal untuk guru
            $bentrokGuru = $this->cekBentrokJadwalGuru(
                $jadwal['id_guru'],
                $jadwal['hari'],
                $waktuMulai,
                $waktuSelesai
            );

            if ($bentrokGuru) {
                $guru = Guru::find($jadwal['id_guru']);
                $bentrokMessages[] = "Jadwal ke-" . ($index + 1) . " bentrok! Guru " . ($guru ? $guru->nama_lengkap : 'Unknown') . " sudah mengajar pada hari " . ucfirst($jadwal['hari']) . " sesi " . $jadwal['session'];
            }

            // Cek bentrok jadwal untuk kelas
            $bentrokKelas = $this->cekBentrokJadwalKelas(
                $jadwal['id_kelas'],
                $jadwal['hari'],
                $waktuMulai,
                $waktuSelesai
            );

            if ($bentrokKelas) {
                $kelas = Kelas::find($jadwal['id_kelas']);
                $bentrokMessages[] = "Jadwal ke-" . ($index + 1) . " bentrok! Kelas " . ($kelas ? $kelas->nama_kelas : 'Unknown') . " sudah memiliki jadwal pada hari " . ucfirst($jadwal['hari']) . " sesi " . $jadwal['session'];
            }
        }

        // Jika ada bentrok, kembalikan pesan error
        if (!empty($bentrokMessages)) {
            return response()->json([
                'success' => false,
                'message' => 'Terdapat bentrok jadwal',
                'errors' => $bentrokMessages
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $jadwalCreated = [];
            
            foreach ($jadwalData as $jadwal) {
                // Get time slot based on session
                $selectedSession = $jadwal['session'] - 1;
                $waktuMulai = $timeSlots[$selectedSession]['start'];
                $waktuSelesai = $timeSlots[$selectedSession]['end'];
                
                $newJadwal = Jadwal::create([
                    'id_kelas' => $jadwal['id_kelas'],
                    'id_mata_pelajaran' => $jadwal['id_mata_pelajaran'],
                    'id_guru' => $jadwal['id_guru'],
                    'id_tahun_ajaran' => $request->id_tahun_ajaran,
                    'hari' => $jadwal['hari'],
                    'waktu_mulai' => $waktuMulai,
                    'waktu_selesai' => $waktuSelesai,
                    'status' => $request->status ?? 'aktif',
                    'dibuat_pada' => now(),
                    'dibuat_oleh' => Auth::user()->username ?? 'system',
                ]);
                
                $jadwalCreated[] = $newJadwal;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => count($jadwalCreated) . ' jadwal pelajaran berhasil ditambahkan',
                'data' => $jadwalCreated
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jadwal = Jadwal::with(['mataPelajaran', 'guru', 'kelas', 'tahunAjaran'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $jadwal,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        // Ambil semua kelas
        $kelas = Kelas::orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();
        
        // Ambil semua mata pelajaran
        $mataPelajaran = MataPelajaran::orderBy('nama')->get();
        
        // Ambil semua guru yang aktif
        $guru = Guru::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        
        // Ambil semua tahun ajaran
        $tahunAjaranList = TahunAjaran::orderBy('aktif', 'desc')->orderBy('nama_tahun_ajaran', 'desc')->get();
        
        // Ambil jadwal yang sudah ada untuk pengecekan bentrok (kecuali jadwal yang sedang diedit)
        $jadwalExisting = Jadwal::with(['kelas', 'mataPelajaran', 'guru'])
            ->where('id_jadwal', '!=', $id)
            ->where('status', 'aktif')
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->orderBy('waktu_mulai')
            ->get();
            
        // Get time slots
        $timeSlots = $this->getTimeSlots();
        
        // Get available days
        $schoolDays = self::SCHOOL_DAYS;
        
        // Determine current session
        $currentSession = 1;
        foreach ($timeSlots as $index => $slot) {
            if ($jadwal->waktu_mulai == $slot['start'] && $jadwal->waktu_selesai == $slot['end']) {
                $currentSession = $index + 1;
                break;
            }
        }
        
        return view('admin.pages.jadwal_pelajaran.edit_jadwal', compact(
            'jadwal', 
            'kelas', 
            'mataPelajaran', 
            'guru', 
            'jadwalExisting', 
            'tahunAjaranList',
            'timeSlots',
            'schoolDays',
            'currentSession'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'id_guru' => 'required|exists:guru,id_guru',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'session' => 'required|integer|min:1|max:' . self::SESSIONS_PER_DAY,
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Get time slot based on session
        $timeSlots = $this->getTimeSlots();
        $selectedSession = $request->session - 1; // Array is 0-indexed
        
        if (!isset($timeSlots[$selectedSession])) {
            return redirect()->back()
                ->with('error', 'Sesi waktu tidak valid')
                ->withInput();
        }
        
        $waktuMulai = $timeSlots[$selectedSession]['start'];
        $waktuSelesai = $timeSlots[$selectedSession]['end'];

        // Check teacher session count if teacher is changed
        if ($jadwal->id_guru != $request->id_guru) {
            $teacherSessionCount = $this->getTeacherSessionCount($request->id_guru);
            if ($teacherSessionCount >= self::MAX_TEACHER_SESSIONS) {
                return redirect()->back()
                    ->with('error', 'Guru sudah mencapai batas maksimum ' . self::MAX_TEACHER_SESSIONS . ' sesi')
                    ->withInput();
            }
        }

        // Cek bentrok jadwal untuk guru (kecuali jadwal yang sedang diedit)
        $bentrokGuru = $this->cekBentrokJadwalGuru(
            $request->id_guru,
            $request->hari,
            $waktuMulai,
            $waktuSelesai,
            $id
        );

        if ($bentrokGuru) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok! Guru sudah mengajar di kelas lain pada waktu yang sama.')
                ->withInput();
        }

        // Cek bentrok jadwal untuk kelas (kecuali jadwal yang sedang diedit)
        $bentrokKelas = $this->cekBentrokJadwalKelas(
            $request->id_kelas,
            $request->hari,
            $waktuMulai,
            $waktuSelesai,
            $id
        );

        if ($bentrokKelas) {
            return redirect()->back()
                ->with('error', 'Jadwal bentrok! Kelas sudah memiliki jadwal lain pada waktu yang sama.')
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $jadwal->update([
                'id_kelas' => $request->id_kelas,
                'id_mata_pelajaran' => $request->id_mata_pelajaran,
                'id_guru' => $request->id_guru,
                'id_tahun_ajaran' => $request->id_tahun_ajaran,
                'hari' => $request->hari,
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'status' => $request->status,
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => Auth::user()->username ?? 'system',
            ]);
            
            DB::commit();
            
            return redirect()->route('jadwal-pelajaran.index')
                ->with('success', 'Jadwal pelajaran berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Jadwal pelajaran berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update status for multiple schedules.
     */
    public function updateStatusBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_ids' => 'required|array',
            'jadwal_ids.*' => 'exists:jadwal,id_jadwal',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $count = Jadwal::whereIn('id_jadwal', $request->jadwal_ids)
                ->update([
                    'status' => $request->status,
                    'diperbarui_pada' => now(),
                    'diperbarui_oleh' => Auth::user()->username ?? 'system',
                ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $count . ' jadwal pelajaran berhasil diperbarui statusnya menjadi ' . $request->status,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status jadwal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update status for all schedules in an academic year.
     */
    public function updateStatusByTahunAjaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $count = Jadwal::where('id_tahun_ajaran', $request->id_tahun_ajaran)
                ->update([
                    'status' => $request->status,
                    'diperbarui_pada' => now(),
                    'diperbarui_oleh' => Auth::user()->username ?? 'system',
                ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $count . ' jadwal pelajaran untuk tahun ajaran ini berhasil diperbarui statusnya menjadi ' . $request->status,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status jadwal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get jadwal by kelas.
     */
    public function getByKelas($kelasId)
    {
        $jadwal = Jadwal::with(['mataPelajaran', 'guru', 'tahunAjaran'])
            ->where('id_kelas', $kelasId)
            ->where('status', 'aktif')
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->orderBy('waktu_mulai')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jadwal,
        ]);
    }

    /**
     * Get jadwal by guru.
     */
    public function getByGuru($guruId)
    {
        $jadwal = Jadwal::with(['kelas', 'mataPelajaran', 'tahunAjaran'])
            ->where('id_guru', $guruId)
            ->where('status', 'aktif')
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->orderBy('waktu_mulai')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jadwal,
        ]);
    }

    /**
     * Get jadwal by mata pelajaran.
     */
    public function getByMataPelajaran($mapelId)
    {
        $jadwal = Jadwal::with(['kelas', 'guru', 'tahunAjaran'])
            ->where('id_mata_pelajaran', $mapelId)
            ->where('status', 'aktif')
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->orderBy('waktu_mulai')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jadwal,
        ]);
    }

    /**
     * Get jadwal by tahun ajaran.
     */
    public function getByTahunAjaran($tahunAjaranId)
    {
        $jadwal = Jadwal::with(['kelas', 'guru', 'mataPelajaran'])
            ->where('id_tahun_ajaran', $tahunAjaranId)
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->orderBy('waktu_mulai')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jadwal,
        ]);
    }

    /**
     * Cek bentrok jadwal untuk guru.
     */
    private function cekBentrokJadwalGuru($idGuru, $hari, $waktuMulai, $waktuSelesai, $idJadwalExclude = null)
    {
        $query = Jadwal::where('id_guru', $idGuru)
            ->where('hari', $hari)
            ->where('status', 'aktif');
            
        if ($idJadwalExclude) {
            $query->where('id_jadwal', '!=', $idJadwalExclude);
        }
        
        return $query->where(function($q) use ($waktuMulai, $waktuSelesai) {
            // Cek apakah waktu mulai atau waktu selesai berada di dalam rentang jadwal yang sudah ada
            $q->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
              ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
              // Cek apakah jadwal yang sudah ada berada di dalam rentang waktu yang baru
              ->orWhere(function($query) use ($waktuMulai, $waktuSelesai) {
                  $query->where('waktu_mulai', '<=', $waktuMulai)
                        ->where('waktu_selesai', '>=', $waktuSelesai);
              });
        })->exists();
    }

    /**
     * Cek bentrok jadwal untuk kelas.
     */
    private function cekBentrokJadwalKelas($idKelas, $hari, $waktuMulai, $waktuSelesai, $idJadwalExclude = null)
    {
        $query = Jadwal::where('id_kelas', $idKelas)
            ->where('hari', $hari)
            ->where('status', 'aktif');
            
        if ($idJadwalExclude) {
            $query->where('id_jadwal', '!=', $idJadwalExclude);
        }
        
        return $query->where(function($q) use ($waktuMulai, $waktuSelesai) {
            // Cek apakah waktu mulai atau waktu selesai berada di dalam rentang jadwal yang sudah ada
            $q->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
              ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
              // Cek apakah jadwal yang sudah ada berada di dalam rentang waktu yang baru
              ->orWhere(function($query) use ($waktuMulai, $waktuSelesai) {
                  $query->where('waktu_mulai', '<=', $waktuMulai)
                        ->where('waktu_selesai', '>=', $waktuSelesai);
              });
        })->exists();
    }

    /**
     * Mendapatkan rekomendasi jadwal berdasarkan guru dan kelas.
     */
    public function getRekomendasiJadwal(Request $request)
    {
        $idGuru = $request->input('id_guru');
        $idKelas = $request->input('id_kelas');
        $hari = $request->input('hari');
        
        if (!$idGuru || !$idKelas || !$hari) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter tidak lengkap',
            ], 400);
        }
        
        // Get all time slots
        $allTimeSlots = $this->getTimeSlots();
        
        // Check teacher session count
        $teacherSessionCount = $this->getTeacherSessionCount($idGuru);
        if ($teacherSessionCount >= self::MAX_TEACHER_SESSIONS) {
            return response()->json([
                'success' => false,
                'message' => 'Guru sudah mencapai batas maksimum ' . self::MAX_TEACHER_SESSIONS . ' sesi',
            ], 400);
        }
        
        // Ambil jadwal guru pada hari tersebut
        $jadwalGuru = Jadwal::where('id_guru', $idGuru)
            ->where('hari', $hari)
            ->where('status', 'aktif')
            ->get(['waktu_mulai', 'waktu_selesai']);
            
        // Ambil jadwal kelas pada hari tersebut
        $jadwalKelas = Jadwal::where('id_kelas', $idKelas)
            ->where('hari', $hari)
            ->where('status', 'aktif')
            ->get(['waktu_mulai', 'waktu_selesai']);
            
        // Filter available time slots
        $availableSlots = [];
        
        foreach ($allTimeSlots as $index => $slot) {
            $bentrokGuru = false;
            $bentrokKelas = false;
            
            // Check for conflicts with teacher's schedule
            foreach ($jadwalGuru as $jg) {
                if (
                    ($slot['start'] >= $jg->waktu_mulai && $slot['start'] < $jg->waktu_selesai) ||
                    ($slot['end'] > $jg->waktu_mulai && $slot['end'] <= $jg->waktu_selesai) ||
                    ($slot['start'] <= $jg->waktu_mulai && $slot['end'] >= $jg->waktu_selesai)
                ) {
                    $bentrokGuru = true;
                    break;
                }
            }
            
            // Check for conflicts with class's schedule
            foreach ($jadwalKelas as $jk) {
                if (
                    ($slot['start'] >= $jk->waktu_mulai && $slot['start'] < $jk->waktu_selesai) ||
                    ($slot['end'] > $jk->waktu_mulai && $slot['end'] <= $jk->waktu_selesai) ||
                    ($slot['start'] <= $jk->waktu_mulai && $slot['end'] >= $jk->waktu_selesai)
                ) {
                    $bentrokKelas = true;
                    break;
                }
            }
            
            // If no conflicts, add to available slots
            if (!$bentrokGuru && !$bentrokKelas) {
                $availableSlots[] = [
                    'session' => $index + 1,
                    'waktu_mulai' => $slot['start'],
                    'waktu_selesai' => $slot['end'],
                    'label' => $slot['label']
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $availableSlots,
        ]);
    }
}
