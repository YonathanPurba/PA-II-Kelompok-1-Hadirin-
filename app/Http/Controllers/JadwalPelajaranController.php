<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JadwalPelajaranController extends Controller
{
    /**
     * Konstanta untuk waktu sesi pelajaran
     */
    const WAKTU_MULAI_PERTAMA = '07:30:00';
    const DURASI_SESI = 45; // dalam menit
    const ISTIRAHAT_ANTAR_SESI = 5; // dalam menit

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter berdasarkan kelas, hari, dan tahun ajaran
        $kelasId = $request->input('kelas');
        $hari = $request->input('hari');
        $tahunAjaranId = $request->input('tahun_ajaran');
        $status = $request->input('status', 'aktif'); // Default ke jadwal aktif

        // Ambil data untuk filter dropdown
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $tahunAjaranList = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();
        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

        // Query jadwal dengan filter
        $query = Jadwal::with(['kelas', 'mataPelajaran', 'guru', 'tahunAjaran']);

        if ($kelasId) {
            $query->where('id_kelas', $kelasId);
        }

        if ($hari) {
            $query->where('hari', $hari);
        }

        if ($tahunAjaranId) {
            $query->where('id_tahun_ajaran', $tahunAjaranId);
        }

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        // Urutkan jadwal
        $jadwalList = $query->orderBy('hari')
            ->orderBy('waktu_mulai')
            ->orderBy('id_kelas')
            ->get();

        // Kelompokkan jadwal berdasarkan hari dan kelas untuk tampilan yang lebih terstruktur
        $jadwalByHariKelas = [];
        foreach ($hariList as $h) {
            $jadwalByHariKelas[$h] = [];
            foreach ($kelasList as $k) {
                $jadwalByHariKelas[$h][$k->id_kelas] = $jadwalList->filter(function ($jadwal) use ($h, $k) {
                    return $jadwal->hari === $h && $jadwal->id_kelas === $k->id_kelas;
                })->sortBy('waktu_mulai')->values();
            }
        }

        return view('admin.pages.jadwal_pelajaran.manajemen_data_jadwal_pelajaran', compact(
            'jadwalList',
            'jadwalByHariKelas',
            'kelasList',
            'tahunAjaranList',
            'hariList'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil data untuk dropdown
        $kelasList = Kelas::whereHas('tahunAjaran', function ($query) {
            $query->where('aktif', true);
        })->orderBy('tingkat')->orderBy('nama_kelas')->get();
        
        $mataPelajaranList = MataPelajaran::orderBy('nama')->get();
        
        $guruList = Guru::where('status', 'aktif')
            ->with('mataPelajaran')
            ->orderBy('nama_lengkap')
            ->get();
        
        $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
        
        // Generate sesi waktu (8 sesi, mulai 07:30, durasi 45 menit, istirahat 5 menit)
        $sesiList = $this->generateSesiWaktu();
        
        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

        return view('admin.pages.jadwal_pelajaran.tambah_jadwal', compact(
            'kelasList',
            'mataPelajaranList',
            'guruList',
            'tahunAjaranAktif',
            'sesiList',
            'hariList'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'jadwal' => 'required|array',
            'jadwal.*.hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'jadwal.*.sesi' => 'required|array',
            'jadwal.*.sesi.*.id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'jadwal.*.sesi.*.id_guru' => 'required|exists:guru,id_guru',
            'jadwal.*.sesi.*.waktu_mulai' => 'required',
            'jadwal.*.sesi.*.waktu_selesai' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan pada input jadwal.');
        }

        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
        if (!$tahunAjaranAktif) {
            return redirect()->back()
                ->with('error', 'Tidak ada tahun ajaran aktif. Silakan aktifkan tahun ajaran terlebih dahulu.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $idKelas = $request->id_kelas;
            $kelas = Kelas::findOrFail($idKelas);
            
            // Simpan jadwal untuk setiap hari dan sesi
            $conflictErrors = [];
            
            foreach ($request->jadwal as $hari => $hariData) {
                if (!isset($hariData['sesi']) || !is_array($hariData['sesi'])) {
                    continue;
                }
                
                foreach ($hariData['sesi'] as $sesiIndex => $sesiData) {
                    // Skip jika tidak ada mata pelajaran yang dipilih
                    if (empty($sesiData['id_mata_pelajaran']) || $sesiData['id_mata_pelajaran'] == '0') {
                        continue;
                    }
                    
                    // Buat jadwal baru
                    $jadwal = new Jadwal();
                    $jadwal->id_kelas = $idKelas;
                    $jadwal->id_mata_pelajaran = $sesiData['id_mata_pelajaran'];
                    $jadwal->id_guru = $sesiData['id_guru'];
                    $jadwal->id_tahun_ajaran = $tahunAjaranAktif->id_tahun_ajaran;
                    $jadwal->hari = $hari;
                    $jadwal->waktu_mulai = $sesiData['waktu_mulai'];
                    $jadwal->waktu_selesai = $sesiData['waktu_selesai'];
                    $jadwal->status = 'aktif';
                    $jadwal->dibuat_pada = now();
                    $jadwal->dibuat_oleh = Auth::user()->username ?? 'system';
                    
                    // Cek konflik jadwal sebelum menyimpan
                    if ($jadwal->hasConflicts()) {
                        $conflicts = $jadwal->getConflicts();
                        foreach ($conflicts as $conflict) {
                            $conflictErrors[] = "Konflik jadwal pada hari " . ucfirst($hari) . " pukul " . 
                                date('H:i', strtotime($sesiData['waktu_mulai'])) . "-" . 
                                date('H:i', strtotime($sesiData['waktu_selesai'])) . 
                                ": " . ($conflict->id_kelas == $idKelas ? 
                                    "Kelas sudah memiliki jadwal dengan " . $conflict->mataPelajaran->nama : 
                                    "Guru " . $conflict->guru->nama_lengkap . " sudah mengajar di kelas " . $conflict->kelas->nama_kelas);
                        }
                        continue;
                    }
                    
                    $jadwal->save();
                }
            }
            
            // Jika ada konflik, rollback dan tampilkan pesan error
            if (!empty($conflictErrors)) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Terdapat konflik jadwal:<br>' . implode('<br>', $conflictErrors))
                    ->withInput();
            }
            
            DB::commit();
            return redirect()->route('jadwal-pelajaran.index')
                ->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyimpan jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jadwal = Jadwal::with(['kelas', 'mataPelajaran', 'guru', 'tahunAjaran'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $jadwal
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jadwal = Jadwal::with(['kelas', 'mataPelajaran', 'guru', 'tahunAjaran'])->findOrFail($id);
        
        // Ambil data untuk dropdown
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mataPelajaranList = MataPelajaran::orderBy('nama')->get();
        $guruList = Guru::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        $tahunAjaranList = TahunAjaran::orderBy('aktif', 'desc')->orderBy('nama_tahun_ajaran', 'desc')->get();
        
        // Generate sesi waktu
        $sesiList = $this->generateSesiWaktu();
        
        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

        return view('admin.pages.jadwal_pelajaran.edit_jadwal', compact(
            'jadwal',
            'kelasList',
            'mataPelajaranList',
            'guruList',
            'tahunAjaranList',
            'sesiList',
            'hariList'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'id_guru' => 'required|exists:guru,id_guru',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $jadwal = Jadwal::findOrFail($id);
            
            // Update jadwal
            $jadwal->id_kelas = $request->id_kelas;
            $jadwal->id_mata_pelajaran = $request->id_mata_pelajaran;
            $jadwal->id_guru = $request->id_guru;
            $jadwal->hari = $request->hari;
            $jadwal->waktu_mulai = $request->waktu_mulai;
            $jadwal->waktu_selesai = $request->waktu_selesai;
            $jadwal->status = $request->status;
            $jadwal->diperbarui_pada = now();
            $jadwal->diperbarui_oleh = Auth::user()->username ?? 'system';
            
            // Cek konflik jadwal sebelum menyimpan
            if ($jadwal->hasConflicts()) {
                $conflicts = $jadwal->getConflicts();
                $conflictMessages = [];
                
                foreach ($conflicts as $conflict) {
                    $conflictMessages[] = "Konflik dengan jadwal " . 
                        $conflict->mataPelajaran->nama . " di kelas " . 
                        $conflict->kelas->nama_kelas . " (" . 
                        date('H:i', strtotime($conflict->waktu_mulai)) . "-" . 
                        date('H:i', strtotime($conflict->waktu_selesai)) . ") " . 
                        "oleh " . $conflict->guru->nama_lengkap;
                }
                
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Terdapat konflik jadwal:<br>' . implode('<br>', $conflictMessages))
                    ->withInput();
            }
            
            $jadwal->save();
            
            DB::commit();
            return redirect()->route('jadwal-pelajaran.index')
                ->with('success', 'Jadwal pelajaran berhasil diperbarui.');
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
                'message' => 'Jadwal berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get jadwal by kelas.
     */
    public function getByKelas($kelasId)
    {
        $jadwal = Jadwal::with(['mataPelajaran', 'guru'])
            ->where('id_kelas', $kelasId)
            ->orderBy('hari')
            ->orderBy('waktu_mulai')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $jadwal
        ]);
    }

    /**
     * Get jadwal by guru.
     */
    public function getByGuru($guruId)
    {
        $jadwal = Jadwal::with(['kelas', 'mataPelajaran'])
            ->where('id_guru', $guruId)
            ->orderBy('hari')
            ->orderBy('waktu_mulai')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $jadwal
        ]);
    }

    /**
     * Get jadwal by tahun ajaran.
     */
    public function getByTahunAjaran($tahunAjaranId)
    {
        $jadwal = Jadwal::with(['kelas', 'mataPelajaran', 'guru'])
            ->where('id_tahun_ajaran', $tahunAjaranId)
            ->orderBy('hari')
            ->orderBy('waktu_mulai')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $jadwal
        ]);
    }

    /**
     * Update status jadwal.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif'
        ]);
        
        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->status = $request->status;
            $jadwal->diperbarui_pada = now();
            $jadwal->diperbarui_oleh = Auth::user()->username ?? 'system';
            $jadwal->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Status jadwal berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batch update status jadwal.
     */
    public function batchUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:jadwal,id_jadwal',
            'status' => 'required|in:aktif,nonaktif'
        ]);
        
        DB::beginTransaction();
        try {
            Jadwal::whereIn('id_jadwal', $request->ids)
                ->update([
                    'status' => $request->status,
                    'diperbarui_pada' => now(),
                    'diperbarui_oleh' => Auth::user()->username ?? 'system'
                ]);
                
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Status jadwal berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate sesi waktu untuk dropdown.
     */
    private function generateSesiWaktu()
    {
        $sesiList = [];
        $waktuMulai = strtotime(self::WAKTU_MULAI_PERTAMA);
        
        for ($i = 1; $i <= 10; $i++) {
            $waktuSelesai = $waktuMulai + (self::DURASI_SESI * 60);
            
            $sesiList[] = [
                'sesi' => $i,
                'label' => "Sesi $i (" . date('H:i', $waktuMulai) . " - " . date('H:i', $waktuSelesai) . ")",
                'waktu_mulai' => date('H:i:s', $waktuMulai),
                'waktu_selesai' => date('H:i:s', $waktuSelesai)
            ];
            
            // Tambahkan istirahat untuk sesi berikutnya
            $waktuMulai = $waktuSelesai + (self::ISTIRAHAT_ANTAR_SESI * 60);
        }
        
        return $sesiList;
    }

    /**
     * Check for schedule conflicts.
     */
    public function checkConflicts(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_guru' => 'required|exists:guru,id_guru',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'id_jadwal' => 'nullable|exists:jadwal,id_jadwal'
        ]);
        
        // Buat objek jadwal sementara untuk pengecekan
        $jadwal = new Jadwal();
        $jadwal->id_kelas = $request->id_kelas;
        $jadwal->id_guru = $request->id_guru;
        $jadwal->hari = $request->hari;
        $jadwal->waktu_mulai = $request->waktu_mulai;
        $jadwal->waktu_selesai = $request->waktu_selesai;
        
        // Jika ini adalah edit jadwal, exclude jadwal yang sedang diedit
        if ($request->id_jadwal) {
            $jadwal->id_jadwal = $request->id_jadwal;
        }
        
        $conflicts = $jadwal->getConflicts();
        
        if ($conflicts->count() > 0) {
            $conflictMessages = [];
            
            foreach ($conflicts as $conflict) {
                $conflictMessages[] = [
                    'id_jadwal' => $conflict->id_jadwal,
                    'kelas' => $conflict->kelas->nama_kelas,
                    'mata_pelajaran' => $conflict->mataPelajaran->nama,
                    'guru' => $conflict->guru->nama_lengkap,
                    'waktu' => date('H:i', strtotime($conflict->waktu_mulai)) . " - " . date('H:i', strtotime($conflict->waktu_selesai)),
                    'message' => "Konflik dengan jadwal " . $conflict->mataPelajaran->nama . 
                        " di kelas " . $conflict->kelas->nama_kelas . 
                        " (" . date('H:i', strtotime($conflict->waktu_mulai)) . "-" . date('H:i', strtotime($conflict->waktu_selesai)) . ") " . 
                        "oleh " . $conflict->guru->nama_lengkap
                ];
            }
            
            return response()->json([
                'success' => false,
                'conflicts' => $conflictMessages
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Tidak ada konflik jadwal.'
        ]);
    }

    /**
     * Get mata pelajaran by guru.
     */
    public function getMataPelajaranByGuru($guruId)
    {
        $guru = Guru::with('mataPelajaran')->findOrFail($guruId);
        
        return response()->json([
            'success' => true,
            'data' => $guru->mataPelajaran
        ]);
    }

    /**
     * Copy jadwal from one class to another.
     */
    public function copyJadwal(Request $request)
    {
        $request->validate([
            'id_kelas_sumber' => 'required|exists:kelas,id_kelas',
            'id_kelas_tujuan' => 'required|exists:kelas,id_kelas|different:id_kelas_sumber'
        ]);
        
        DB::beginTransaction();
        try {
            // Ambil jadwal dari kelas sumber
            $jadwalSumber = Jadwal::where('id_kelas', $request->id_kelas_sumber)->get();
            
            if ($jadwalSumber->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Kelas sumber tidak memiliki jadwal untuk disalin.');
            }
            
            // Hapus jadwal yang sudah ada di kelas tujuan
            Jadwal::where('id_kelas', $request->id_kelas_tujuan)->delete();
            
            // Salin jadwal ke kelas tujuan
            foreach ($jadwalSumber as $jadwal) {
                $newJadwal = $jadwal->replicate();
                $newJadwal->id_kelas = $request->id_kelas_tujuan;
                $newJadwal->dibuat_pada = now();
                $newJadwal->dibuat_oleh = Auth::user()->username ?? 'system';
                $newJadwal->diperbarui_pada = null;
                $newJadwal->diperbarui_oleh = null;
                $newJadwal->save();
            }
            
            DB::commit();
            
            return redirect()->route('jadwal-pelajaran.index')
                ->with('success', 'Jadwal berhasil disalin ke kelas tujuan.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal menyalin jadwal: ' . $e->getMessage());
        }
    }
}
