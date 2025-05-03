<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user(); // user yang login

        $guru = \App\Models\Guru::with(['user', 'mataPelajaran', 'kelas'])
            ->where('id_user', $user->id_user)
            ->first();

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $guru->nama_lengkap, // ambil dari relasi user
                'nip' => $guru->nip,          // dari tabel guru
            ],
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50|unique:guru,nip',
            'bidang_studi' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'nomor_telepon' => 'nullable|string|max:15',
            'mata_pelajaran' => 'nullable|array',
            'mata_pelajaran.*' => 'exists:mata_pelajaran,id_mata_pelajaran',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Buat user terlebih dahulu
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'id_role' => 2, // Role guru
                'nomor_telepon' => $request->nomor_telepon,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            // Buat data guru
            $guru = Guru::create([
                'id_user' => $user->id_user,
                'nama_lengkap' => $request->nama_lengkap,
                'nip' => $request->nip,
                'bidang_studi' => $request->bidang_studi,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            // Jika ada mata pelajaran, tambahkan relasi
            if ($request->has('mata_pelajaran') && is_array($request->mata_pelajaran)) {
                $mataPelajaranData = [];
                foreach ($request->mata_pelajaran as $idMataPelajaran) {
                    $mataPelajaranData[$idMataPelajaran] = [
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'API',
                    ];
                }
                $guru->mataPelajaran()->attach($mataPelajaranData);
            }

            DB::commit();

            // Load relasi untuk response
            $guru->load(['user', 'mataPelajaran']);

            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil ditambahkan',
                'data' => $guru,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan guru',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $guru = Guru::with(['user', 'mataPelajaran', 'jadwal.kelas', 'jadwal.mataPelajaran'])->find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $guru,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $guru = Guru::with('user')->find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'string|max:255',
            'nip' => 'nullable|string|max:50|unique:guru,nip,' . $id . ',id_guru',
            'bidang_studi' => 'nullable|string|max:255',
            'username' => 'string|max:255|unique:users,username,' . $guru->id_user . ',id_user',
            'password' => 'nullable|string|min:6',
            'nomor_telepon' => 'nullable|string|max:15',
            'mata_pelajaran' => 'nullable|array',
            'mata_pelajaran.*' => 'exists:mata_pelajaran,id_mata_pelajaran',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update user data
            $userData = [];

            if ($request->has('username')) {
                $userData['username'] = $request->username;
            }

            if ($request->has('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($request->has('nomor_telepon')) {
                $userData['nomor_telepon'] = $request->nomor_telepon;
            }

            if (!empty($userData)) {
                $userData['diperbarui_pada'] = now();
                $userData['diperbarui_oleh'] = 'API';
                $guru->user->update($userData);
            }

            // Update guru data
            $guruData = [];

            if ($request->has('nama_lengkap')) {
                $guruData['nama_lengkap'] = $request->nama_lengkap;
            }

            if ($request->has('nip')) {
                $guruData['nip'] = $request->nip;
            }

            if ($request->has('bidang_studi')) {
                $guruData['bidang_studi'] = $request->bidang_studi;
            }

            if (!empty($guruData)) {
                $guruData['diperbarui_pada'] = now();
                $guruData['diperbarui_oleh'] = 'API';
                $guru->update($guruData);
            }

            // Update mata pelajaran jika ada
            if ($request->has('mata_pelajaran')) {
                $mataPelajaranData = [];
                foreach ($request->mata_pelajaran as $idMataPelajaran) {
                    $mataPelajaranData[$idMataPelajaran] = [
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'API',
                    ];
                }
                $guru->mataPelajaran()->sync($mataPelajaranData);
            }

            DB::commit();

            // Refresh model untuk mendapatkan data terbaru
            $guru = Guru::with(['user', 'mataPelajaran'])->find($id);

            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil diperbarui',
                'data' => $guru,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui guru',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Simpan id_user untuk menghapus user setelah guru dihapus
            $idUser = $guru->id_user;

            // Hapus guru
            $guru->delete();

            // Hapus user terkait
            User::where('id_user', $idUser)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus guru',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get jadwal mengajar guru.
     */
    /**
     * Get jadwal mengajar guru.
     */
    public function getJadwal($id)
    {
        // Mencari pengguna berdasarkan ID_user
        $user = User::find($id); // Menyesuaikan pencarian dengan ID_user

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan.'], 404);
        }

        // Asumsi bahwa ID_user dapat menghubungkan ke Guru
        $guru = $user->guru; // Relasi antara User dan Guru

        if (!$guru) {
            return response()->json(['message' => 'Guru tidak ditemukan.'], 404);
        }

        // Mengambil jadwal guru berdasarkan id_guru
        $jadwal = Jadwal::where('id_guru', $guru->id_guru)
            ->with('kelas', 'mataPelajaran') // Menambahkan relasi kelas dan mata pelajaran
            ->get();

        // Mengubah data jadwal agar sesuai dengan format yang diinginkan
        $formattedJadwal = $jadwal->map(function ($item) {
            $status = $this->getStatusJadwal($item->waktu_mulai, $item->waktu_selesai);

            return [
                'kelas' => $item->kelas->nama_kelas, // Misalkan nama kelas berada di model Kelas
                'waktu' => $item->waktu_mulai->format('H:i') . ' - ' . $item->waktu_selesai->format('H:i'),
                'status' => $status,
                'color' => $this->getStatusColor($status), // Menentukan warna berdasarkan status
            ];
        });

        return response()->json(['data' => $formattedJadwal]);
    }


    // Metode untuk menentukan status jadwal berdasarkan waktu sekarang
    private function getStatusJadwal($waktuMulai, $waktuSelesai)
    {
        $now = now(); // Waktu sekarang

        if ($now->isBefore($waktuMulai)) {
            return 'Mendatang';
        }

        if ($now->isBetween($waktuMulai, $waktuSelesai)) {
            return 'Sedang Berjalan';
        }

        return 'Selesai';
    }

    // Metode untuk menentukan warna berdasarkan status jadwal
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'Sedang Berjalan':
                return '#1976D2'; // Warna biru
            case 'Mendatang':
                return '#BDBDBD'; // Warna abu-abu
            case 'Selesai':
                return '#1B3C2F'; // Warna hijau
            default:
                return '#FFFFFF'; // Default
        }
    }

    public function getProfile($id)
    {
        // Mencari pengguna berdasarkan ID_user
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan.'], 404);
        }

        // Asumsi bahwa ID_user memiliki relasi dengan Guru
        $guru = $user->guru; // Pastikan relasi 'guru' didefinisikan di model User

        if (!$guru) {
            return response()->json(['message' => 'Data guru tidak ditemukan.'], 404);
        }

        return response()->json([
            'user' => [
                'nama_lengkap' => $guru->nama_lengkap,
                'nip' => $guru->nip,
                'nomor_telepon' => $guru->nomor_telepon,
                'bidang_studi' => $guru->bidang_studi,
            ]
        ], 200);
    }
    public function getNotifikasiSuratIzin($id_user)
    {
        // Ambil user dengan relasi guru -> kelas -> siswa -> suratIzin
        $user = User::with('guru.kelas.siswa.suratIzin')->find($id_user);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
                'data' => ['data' => []]
            ], 404);
        }

        $guru = $user->guru;

        if (!$guru || $guru->kelas->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru atau kelas tidak ditemukan.',
                'data' => ['data' => []]
            ], 404);
        }

        $notifikasi = [];

        // Jika guru->kelas adalah koleksi, lakukan iterasi
        $kelasList = $guru->kelas;

        // Tangani jika 'kelas' adalah satuan (bukan collection)
        if (!$kelasList instanceof \Illuminate\Support\Collection) {
            $kelasList = collect([$kelasList]);
        }

        foreach ($kelasList as $kelas) {
            foreach ($kelas->siswa as $siswa) {
                foreach ($siswa->suratIzin as $izin) {
                    $notifikasi[] = [
                        'nama_siswa' => $siswa->nama,
                        'jenis' => $izin->jenis,
                        'tanggal_mulai' => Carbon::parse($izin->tanggal_mulai)->format('Y-m-d H:i:s'),
                        'tanggal_selesai' => Carbon::parse($izin->tanggal_selesai)->format('Y-m-d H:i:s'),
                        'alasan' => $izin->alasan,
                        'status' => $izin->status,
                        'dibuat_pada' => Carbon::parse($izin->dibuat_pada)->format('Y-m-d H:i:s'),
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi surat izin berhasil diambil.',
            'data' => ['data' => $notifikasi]
        ]);
    }
}
