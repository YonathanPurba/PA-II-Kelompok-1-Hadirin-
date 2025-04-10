<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratIzinController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\JadwalPelajaranController;
// use App\Http\Controllers\UserController;

// Menampilkan formulir login
Route::get('/', [AuthController::class, 'showLoginForm']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Memproses data login
Route::post('/login', [AuthController::class, 'processLogin']);

// Protected Routes - pakai middleware auth (session)
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/admin/beranda', [DashboardController::class, 'index'])->name('admin.beranda');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Users
    Route::get('/users', [UserController::class, 'index']);

    // Guru
    Route::resource('guru', GuruController::class);

    // Siswa
    Route::resource('siswa', SiswaController::class);
    Route::get('/siswa/kelas/{kelasId}', [SiswaController::class, 'getByKelas']);
    Route::get('/siswa/orang-tua/{orangTuaId}', [SiswaController::class, 'getByOrangTua']);

    // Orang Tua
    Route::resource('orang-tua', OrangTuaController::class);

    // Kelas
    Route::resource('kelas', KelasController::class);

    // Mata Pelajaran
    Route::resource('mata-pelajaran', MataPelajaranController::class);

    // Jadwal Pelajaran
    Route::resource('jadwal-pelajaran', JadwalPelajaranController::class);
    Route::get('/jadwal-pelajaran/kelas/{kelasId}', [JadwalPelajaranController::class, 'getByKelas']);
    Route::get('/jadwal-pelajaran/guru/{guruId}', [JadwalPelajaranController::class, 'getByGuru']);

    // Absensi
    Route::resource('absensi', AbsensiController::class);
    Route::get('/absensi/siswa/{siswaId}', [AbsensiController::class, 'getBySiswa']);
    Route::get('/absensi/jadwal/{jadwalId}/{tanggal?}', [AbsensiController::class, 'getByJadwal']);
    Route::post('/absensi/bulk', [AbsensiController::class, 'createBulk']);

    // Surat Izin
    Route::resource('surat-izin', SuratIzinController::class);
    Route::get('/surat-izin/siswa/{siswaId}', [SuratIzinController::class, 'getBySiswa']);
    Route::get('/surat-izin/guru/{guruId}', [SuratIzinController::class, 'getByGuru']);
    Route::put('/surat-izin/{id}/approve', [SuratIzinController::class, 'approve']);
    Route::put('/surat-izin/{id}/reject', [SuratIzinController::class, 'reject']);
});


// Route::get('/api/login', function () {
//     return view('admin.pages.dokumen');
// });

// Route::get('/admin/jadwal_pelajaran', function () {
//     return view('admin.pages.jadwal_pelajaran');
// });


// Route::get('/admin/dokumen', function () {
//     return view('admin.pages.dokumen');
// });

// Route::get('/admin/guru/manajemen_data_guru', function () {
//     return view('admin.pages.guru.manajemen_data_guru');
// });

// Route::get('/admin/guru/tambah_guru', function () {
//     return view('admin.pages.guru.tambah_guru');
// });

// Route::get('/admin/orang_tua/manajemen_data_orang_tua', function () {
//     return view('admin.pages.orang_tua.manajemen_data_orang_tua');
// });

// Route::get('/admin/orang_tua/data-orang-tua', function () {
//     return view('admin.pages.orang_tua.data-orang-tua');
// });

// Route::get('/admin/orang_tua/tambah-orang-tua', function () {
//     return view('admin.pages.orang_tua.tambah-orang-tua');
// });

// Route::get('/admin/siswa/manajemen_data_siswa', function () {
//     return view('admin.pages.siswa.manajemen_data_siswa');
// });

// Route::get('/admin/siswa/data-siswa', function () {
//     return view('admin.pages.siswa.data-siswa');
// });

// Route::get('/admin/rekapitulasi/rekapitulasi', function () {
//     return view('admin.pages.rekapitulasi.rekapitulasi');
// });

// Route::get('/admin/rekapitulasi/kelas-rekapitulasi', function () {
//     return view('admin.pages.rekapitulasi.kelas-rekapitulasi');
// });
