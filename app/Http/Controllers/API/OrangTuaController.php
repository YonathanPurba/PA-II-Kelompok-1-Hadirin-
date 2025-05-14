<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\OrangTua;
use App\Models\SuratIzin;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OrangTuaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orangTua = OrangTua::with(['user', 'siswa'])->get();

        return response()->json([
            'success' => true,
            'data' => $orangTua,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'nomor_telepon' => 'nullable|string|max:15',
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
                'id_role' => 3, // Role orang tua
                'nomor_telepon' => $request->nomor_telepon,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            // Buat data orang tua
            $orangTua = OrangTua::create([
                'id_user' => $user->id_user,
                'nama_lengkap' => $request->nama_lengkap,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'API',
            ]);

            DB::commit();

            // Load relasi untuk response
            $orangTua->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Orang tua berhasil ditambahkan',
                'data' => $orangTua,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan orang tua',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $orangTua = OrangTua::with(['user', 'siswa.kelas'])->find($id);

        if (!$orangTua) {
            return response()->json([
                'success' => false,
                'message' => 'Orang tua tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $orangTua,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $orangTua = OrangTua::with('user')->find($id);

        if (!$orangTua) {
            return response()->json([
                'success' => false,
                'message' => 'Orang tua tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'string|max:255',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'username' => 'string|max:255|unique:users,username,' . $orangTua->id_user . ',id_user',
            'password' => 'nullable|string|min:6',
            'nomor_telepon' => 'nullable|string|max:15',
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
                $orangTua->user->update($userData);
            }

            // Update orang tua data
            $orangTuaData = [];

            if ($request->has('nama_lengkap')) {
                $orangTuaData['nama_lengkap'] = $request->nama_lengkap;
            }

            if ($request->has('alamat')) {
                $orangTuaData['alamat'] = $request->alamat;
            }

            if ($request->has('pekerjaan')) {
                $orangTuaData['pekerjaan'] = $request->pekerjaan;
            }

            if (!empty($orangTuaData)) {
                $orangTuaData['diperbarui_pada'] = now();
                $orangTuaData['diperbarui_oleh'] = 'API';
                $orangTua->update($orangTuaData);
            }

            DB::commit();

            // Refresh model untuk mendapatkan data terbaru
            $orangTua = OrangTua::with(['user', 'siswa'])->find($id);

            return response()->json([
                'success' => true,
                'message' => 'Orang tua berhasil diperbarui',
                'data' => $orangTua,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui orang tua',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $orangTua = OrangTua::find($id);

        if (!$orangTua) {
            return response()->json([
                'success' => false,
                'message' => 'Orang tua tidak ditemukan',
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Simpan id_user untuk menghapus user setelah orang tua dihapus
            $idUser = $orangTua->id_user;

            // Hapus orang tua
            $orangTua->delete();

            // Hapus user terkait
            User::where('id_user', $idUser)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orang tua berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus orang tua',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get siswa by orang tua.
     */
    public function getSiswa($id)
    {
        $orangTua = OrangTua::find($id);

        if (!$orangTua) {
            return response()->json([
                'success' => false,
                'message' => 'Orang tua tidak ditemukan',
            ], 404);
        }

        $siswa = $orangTua->siswa()->with('kelas')->get();

        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }

    public function getDaftarAnak($id_user)
    {
        try {
            // Cari orang tua berdasarkan id_user
            $orangTua = OrangTua::where('id_user', $id_user)->first();

            if (!$orangTua) {
                return response()->json([
                    'success' => false,
                    'message' => 'Orang tua tidak ditemukan',
                ], 404);
            }

            // Ambil semua siswa yang terkait dengan orang tua
            $daftarAnak = Siswa::with('kelas')  // Pastikan relasi dengan kelas sudah benar
                ->where('id_orangtua', $orangTua->id_orangtua)
                ->get();

            // Format data yang akan dikirimkan
            $data = $daftarAnak->map(function ($anak) {
                return [
                    'id_siswa' => $anak->id_siswa,
                    'nama' => $anak->nama,
                    'nis' => $anak->nis,
                    'id_orangtua' => $anak->id_orangtua,
                    'id_kelas' => $anak->id_kelas,
                    'id_tahun_ajaran' => $anak->id_tahun_ajaran,
                    'tempat_lahir' => $anak->tempat_lahir,
                    'tanggal_lahir' => $anak->tanggal_lahir,
                    'jenis_kelamin' => $anak->jenis_kelamin,
                    'alamat' => $anak->alamat,
                    'status' => $anak->status,
                    // 'dibuat_pada' => $anak->created_at->toIso8601String(),
                    'dibuat_oleh' => $anak->created_by,
                    // 'diperbarui_pada' => $anak->updated_at->toIso8601String(),
                    'diperbarui_oleh' => $anak->updated_by,
                    'kelas' => [
                        'id_kelas' => $anak->kelas->id_kelas,
                        'nama_kelas' => $anak->kelas->nama_kelas,
                        'tingkat' => $anak->kelas->tingkat,
                        'id_guru' => $anak->kelas->id_guru,
                        'id_tahun_ajaran' => $anak->kelas->id_tahun_ajaran,
                        // 'dibuat_pada' => $anak->kelas->created_at->toIso8601String(),
                        'dibuat_oleh' => $anak->kelas->created_by,
                        // 'diperbarui_pada' => $anak->kelas->updated_at->toIso8601String(),
                        'diperbarui_oleh' => $anak->kelas->updated_by,
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data anak',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getNotifikasi($id)
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID pengguna tidak disertakan.'
            ], 400);
        }

        $notifikasi = Notifikasi::where('id_user', $id)
            ->orderByDesc('dibuat_pada')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_notifikasi,
                    'judul' => $item->judul,
                    'pesan' => $item->pesan,
                    'dibuat_pada' => \Carbon\Carbon::parse($item->dibuat_pada)->toIso8601String(), // format ISO 8601
                    'dibaca' => (bool) $item->dibaca,
                    'status' => $item->tipe ?? null, // opsional: jika kolom tersedia
                    'jenis' => $item->jenis ?? null,   // opsional: jika kolom tersedia
                    'tanggal_mulai' => $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->toIso8601String() : null,
                    'tanggal_selesai' => $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->toIso8601String() : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }
    public function bacaNotifikasi($idNotifikasi)
    {
        if (!$idNotifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'ID notifikasi tidak disertakan.'
            ], 400);
        }

        $notifikasi = Notifikasi::find($idNotifikasi);

        if (!$notifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.'
            ], 404);
        }

        // Update status 'dibaca'
        $notifikasi->dibaca = true;
        // $notifikasi->waktu_dibaca = now(); // opsional
        $notifikasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil ditandai sebagai dibaca.'
        ]);
    }

    public function getProfile($id)
    {
        try {
            // Ambil data user berdasarkan ID dengan relasi orang tua
            $user = User::with('orangTua') // pastikan relasi 'orangTua' ada di model User
                ->where('id_user', $id)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil profil.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function riwayatOrangTua($id)
    {
        try {
            // Mengambil id_orangtua berdasarkan id_user
            $orangTua = OrangTua::where('id_user', $id)->first();

            // Jika orang tua tidak ditemukan
            if (!$orangTua) {
                return response()->json([
                    'success' => false,
                    'message' => 'Orang tua tidak ditemukan untuk user ini.',
                    'data' => [],
                ], 404);
            }

            // Mengambil riwayat surat izin berdasarkan id_orangtua dengan relasi siswa
            $data = SuratIzin::where('id_orangtua', $orangTua->id_orangtua)
                ->with('siswa') // Memuat relasi siswa
                ->orderBy('tanggal_mulai', 'desc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Riwayat surat izin tidak ditemukan',
                    'data' => [],
                ], 404);
            }

            // Menambahkan data siswa ke dalam setiap surat izin
            $dataWithSiswa = $data->map(function ($item) {
                return [
                    'id_siswa' => $item->id_siswa,
                    'id_surat_izin' => $item->id_surat_izin,
                    'nama_siswa' => $item->siswa->nama, // Menambahkan nama siswa
                    'id_orangtua' => $item->id_orangtua,
                    'jenis' => $item->jenis,
                    'tanggal_mulai' => $item->tanggal_mulai,
                    'tanggal_selesai' => $item->tanggal_selesai,
                    'alasan' => $item->alasan,
                    'file_lampiran' => $item->file_lampiran,
                    'status' => $item->status,
                    'dibuat_pada' => $item->dibuat_pada,
                    'dibuat_oleh' => $item->dibuat_oleh,
                    'diperbarui_pada' => $item->diperbarui_pada,
                    'diperbarui_oleh' => $item->diperbarui_oleh,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil riwayat surat izin',
                'data' => $dataWithSiswa,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function presentaseKehadiran($anakId)
    {
        try {
            $hadir = Absensi::where('id_siswa', $anakId)->where('status', 'hadir')->count();
            $izin = Absensi::where('id_siswa', $anakId)->where('status', 'izin')->count();
            $sakit = Absensi::where('id_siswa', $anakId)->where('status', 'sakit')->count();
            $alpa = Absensi::where('id_siswa', $anakId)->where('status', 'alpa')->count();

            $totalSesi = $hadir + $izin + $sakit + $alpa;

            // Jika tidak ada data, set semua persentase ke 0
            if ($totalSesi == 0) {
                return response()->json([
                    'status' => 'success',
                    'id_siswa' => $anakId,
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alpa' => 0,
                    'jumlah' => [
                        'hadir' => 0,
                        'izin' => 0,
                        'sakit' => 0,
                        'alpa' => 0,
                        'total' => 0,
                    ],
                    'message' => 'Belum ada data absensi.',
                ]);
            }

            return response()->json([
                'status' => 'success',
                'id_siswa' => $anakId,
                'hadir' => ($hadir / $totalSesi) * 100,
                'izin' => ($izin / $totalSesi) * 100,
                'sakit' => ($sakit / $totalSesi) * 100,
                'alpa' => ($alpa / $totalSesi) * 100,
                'jumlah' => [
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpa' => $alpa,
                    'total' => $totalSesi,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function riwayatAbsensi($id)
    {
        try {
            $siswa = Siswa::with([
                'absensi' => function ($query) {
                    $query->orderBy('tanggal', 'desc');
                },
                'absensi.jadwal',
                'absensi.jadwal.mataPelajaran',
                'absensi.jadwal.guru'
            ])->findOrFail($id);

            $absensi = $siswa->absensi->map(function ($absen) {
                $waktuMulai = $absen->jadwal?->waktu_mulai;
                $waktuSelesai = $absen->jadwal?->waktu_selesai;

                return [
                    'tanggal' => $absen->tanggal,
                    'status' => $absen->status,
                    'catatan' => $absen->catatan,
                    'jadwal' => [
                        'waktu_mulai' => $waktuMulai ? Carbon::parse($waktuMulai)->format('H:i') : null,
                        'waktu_selesai' => $waktuSelesai ? Carbon::parse($waktuSelesai)->format('H:i') : null,
                        'mata_pelajaran' => [
                            'nama' => $absen->jadwal?->mataPelajaran?->nama ?? '-',
                        ],
                        'guru' => [
                            'nama_lengkap' => $absen->jadwal?->guru?->nama_lengkap ?? '-',
                        ],
                    ],
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $absensi,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil riwayat absensi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getJadwalAnak($id)
    {
        try {
            // Ambil data siswa dengan relasi kelas dan jadwal
            $siswa = Siswa::with([
                'kelas.jadwal.mataPelajaran',
                'kelas.jadwal.guru'
            ])->findOrFail($id);

            // Ambil jadwal dari kelas siswa
            $jadwal = $siswa->kelas->jadwal->map(function ($item) {
                $waktuMulai = $item->waktu_mulai;
                $waktuSelesai = $item->waktu_selesai;

                return [
                    'hari' => $item->hari,
                    'waktu_mulai' => $waktuMulai ? Carbon::parse($waktuMulai)->format('H:i') : null,
                    'waktu_selesai' => $waktuSelesai ? Carbon::parse($waktuSelesai)->format('H:i') : null,
                    'mata_pelajaran' => $item->mataPelajaran->nama ?? '-',
                    'guru' => $item->guru->nama_lengkap ?? '-',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $jadwal,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil jadwal anak: ' . $e->getMessage(),
            ], 500);
        }
    }
}
