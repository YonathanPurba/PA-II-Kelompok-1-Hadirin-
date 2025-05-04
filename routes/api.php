<?php

use App\Http\Controllers\Api\RegisterTest;
use App\Http\Controllers\API\AbsensiController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GuruController;
use App\Http\Controllers\API\JadwalController;
use App\Http\Controllers\API\KelasController;
use App\Http\Controllers\API\MataPelajaranController;
use App\Http\Controllers\API\NotifikasiController;
use App\Http\Controllers\API\OrangtuaController;
use App\Http\Controllers\API\RekapAbsensiController;
use App\Http\Controllers\API\SiswaController;
use App\Http\Controllers\API\StafController;
use App\Http\Controllers\API\SuratIzinController;
use App\Http\Controllers\API\TahunAjaranController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes

// Register
Route::post("/register", [RegisterTest::class, "register"]);
// Login
Route::post('/login', [AuthController::class, 'login']);

Route::get('/test-connection', function () {
    return response()->json([
        'success' => true,
        'message' => 'API connection is successful!',
    ]);
});

 // Notifikasi
 Route::prefix('notifikasi')->group(function () {
    Route::get('/', [NotifikasiController::class, 'index']);
    Route::post('/', [NotifikasiController::class, 'store']);
    Route::get('/{id}', [NotifikasiController::class, 'show']);
    Route::put('/{id}/read', [NotifikasiController::class, 'markAsRead']);
    Route::put('/read-all', [NotifikasiController::class, 'markAllAsRead']);
    Route::get('/unread-count', [NotifikasiController::class, 'getUnreadCount']);
    Route::delete('/{id}', [NotifikasiController::class, 'destroy']);
    Route::get('/user/{userId}', [NotifikasiController::class, 'getByUser']);
});


// dummy data notifikasi
Route::post('/notifikasi', [NotifikasiController::class, 'store']);


// dummy data guru get jadwal|
Route::get('guru/{id}/jadwal', [GuruController::class, 'getJadwal']);

// save fcm token
Route::post('/save_fcm_token', [AuthController::class, 'saveFcmToken']);

// get fcm token
Route::post('/get_fcm_token_by_id', [NotifikasiController::class, 'getFcmTokenById']);

// Protected routes - menggunakan middleware 
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    // Akun Siapa yang sedang login
    Route::get('/me', [AuthController::class, 'me']);
    

    // Absensi
    Route::prefix('absensi')->group(function () {
        Route::get('/', [AbsensiController::class, 'index']);
        Route::post('/', [AbsensiController::class, 'store']);
        Route::get('/{id}', [AbsensiController::class, 'show']);
        Route::put('/{id}', [AbsensiController::class, 'update']);
        Route::delete('/{id}', [AbsensiController::class, 'destroy']);
        Route::get('/siswa/detail', [AbsensiController::class, 'getByStudent']);
        Route::get('/kelas/detail', [AbsensiController::class, 'getByClass']);
        Route::get('/summary', [AbsensiController::class, 'getSummary']);
        Route::get('/today', [AbsensiController::class, 'getToday']);
    });
    
    // Jadwal
    Route::prefix('jadwal')->group(function () {
        Route::get('/', [JadwalController::class, 'index']);
        Route::post('/', [JadwalController::class, 'store']);
        Route::get('/{id}', [JadwalController::class, 'show']);
        Route::put('/{id}', [JadwalController::class, 'update']);
        Route::delete('/{id}', [JadwalController::class, 'destroy']);
        Route::get('/guru/detail', [JadwalController::class, 'getByTeacher']);
        Route::get('/kelas/detail', [JadwalController::class, 'getByClass']);
        Route::get('/today', [JadwalController::class, 'getTodaySchedule']);
    });
    
    // Siswa
    Route::prefix('siswa')->group(function () {
        Route::get('/', [SiswaController::class, 'index']);
        Route::post('/', [SiswaController::class, 'store']);
        Route::get('/{id}', [SiswaController::class, 'show']);
        Route::put('/{id}', [SiswaController::class, 'update']);
        Route::delete('/{id}', [SiswaController::class, 'destroy']);
        Route::get('/orangtua/detail', [SiswaController::class, 'getByParent']);
        Route::get('/kelas/{kelasId}', [SiswaController::class, 'getByKelas']);
        Route::get('/search', [SiswaController::class, 'search']);
    });
    
    // Surat Izin
    Route::prefix('surat-izin')->group(function () {
        Route::get('/', [SuratIzinController::class, 'index']);
        Route::post('/', [SuratIzinController::class, 'store']);
        Route::get('/{id}', [SuratIzinController::class, 'show']);
        Route::put('/{id}', [SuratIzinController::class, 'update']);
        Route::delete('/{id}', [SuratIzinController::class, 'destroy']);
        Route::put('/{id}/status', [SuratIzinController::class, 'updateStatus']);
        Route::get('/siswa/{siswaId}', [SuratIzinController::class, 'getBySiswa']);
        Route::get('/orangtua/{orangtuaId}', [SuratIzinController::class, 'getByOrangtua']);
    });
    
    // Rekap Absensi
    Route::prefix('rekap-absensi')->group(function () {
        Route::get('/', [RekapAbsensiController::class, 'index']);
        Route::post('/generate', [RekapAbsensiController::class, 'generateRekap']);
        Route::get('/{id}', [RekapAbsensiController::class, 'show']);
        Route::get('/kelas/detail', [RekapAbsensiController::class, 'getByClass']);
        Route::get('/siswa/detail', [RekapAbsensiController::class, 'getByStudent']);
        Route::get('/bulan/{bulan}/tahun/{tahun}', [RekapAbsensiController::class, 'getByPeriod']);
        Route::get('/export', [RekapAbsensiController::class, 'exportRekap']);
    });
    
   
    
    
    // Kelas Routes
    Route::prefix('kelas')->group(function () {
        Route::get('/', [KelasController::class, 'index']);
        Route::post('/', [KelasController::class, 'store']);
        Route::get('/{id}', [KelasController::class, 'show']);
        Route::put('/{id}', [KelasController::class, 'update']);
        Route::delete('/{id}', [KelasController::class, 'destroy']);
        
        // Additional useful routes
        Route::get('/tahun-ajaran/{tahunAjaranId}', [KelasController::class, 'getByTahunAjaran']);
        Route::get('/{id}/siswa', [KelasController::class, 'getSiswa']);
        Route::get('/{id}/jadwal', [KelasController::class, 'getJadwal']);
        Route::get('/{id}/wali-kelas', [KelasController::class, 'getWaliKelas']);
        Route::get('/tingkat/{tingkat}', [KelasController::class, 'getByTingkat']);
        Route::get('/search', [KelasController::class, 'search']);
    });

    // Guru Routes
    Route::prefix('guru')->group(function () {
        Route::get('/', [GuruController::class, 'index']);
        Route::post('/', [GuruController::class, 'store']);
        Route::get('/{id}', [GuruController::class, 'show']);
        Route::put('/{id}', [GuruController::class, 'update']);
        Route::delete('/{id}', [GuruController::class, 'destroy']);
        
        Route::get('/profile/{id}', [GuruController::class, 'getProfile']);
        Route::get('{id_user}/notifikasi-surat-izin', [GuruController::class, 'getNotifikasiSuratIzin']);

        // Additional useful routes
        // Route::get('/{id}/jadwal', [GuruController::class, 'getJadwal']);
        Route::get('/{id}/mata-pelajaran', [GuruController::class, 'getMataPelajaran']);
        Route::get('/{id}/kelas-wali', [GuruController::class, 'getKelasWali']);
        Route::get('/search', [GuruController::class, 'search']);
        Route::get('/by-user/{userId}', [GuruController::class, 'getByUserId']);
    });

    // Orangtua Routes
    Route::prefix('orangtua')->group(function () {
        Route::get('/', [OrangtuaController::class, 'index']);
        Route::post('/', [OrangtuaController::class, 'store']);
        Route::get('/{id}', [OrangtuaController::class, 'show']);
        Route::put('/{id}', [OrangtuaController::class, 'update']);
        Route::delete('/{id}', [OrangtuaController::class, 'destroy']);
        
        // Additional useful routes
        Route::get('/{id}/siswa', [OrangtuaController::class, 'getSiswa']);
        Route::get('/by-user/{userId}', [OrangtuaController::class, 'getByUserId']);
        Route::get('/search', [OrangtuaController::class, 'search']);
    });

    // Mata Pelajaran Routes
    Route::prefix('mata-pelajaran')->group(function () {
        Route::get('/', [MataPelajaranController::class, 'index']);
        Route::post('/', [MataPelajaranController::class, 'store']);
        Route::get('/{id}', [MataPelajaranController::class, 'show']);
        Route::put('/{id}', [MataPelajaranController::class, 'update']);
        Route::delete('/{id}', [MataPelajaranController::class, 'destroy']);
        
        // Additional useful routes
        Route::get('/by-tingkat/{tingkat}', [MataPelajaranController::class, 'getByTingkat']);
        Route::get('/{id}/guru', [MataPelajaranController::class, 'getGuru']);
        Route::get('/search', [MataPelajaranController::class, 'search']);
        Route::get('/kode/{kode}', [MataPelajaranController::class, 'getByKode']);
    });

    // Tahun Ajaran Routes
    Route::prefix('tahun-ajaran')->group(function () {
        Route::get('/', [TahunAjaranController::class, 'index']);
        Route::post('/', [TahunAjaranController::class, 'store']);
        Route::get('/{id}', [TahunAjaranController::class, 'show']);
        Route::put('/{id}', [TahunAjaranController::class, 'update']);
        Route::delete('/{id}', [TahunAjaranController::class, 'destroy']);
        
        // Additional useful routes
        Route::put('/{id}/set-active', [TahunAjaranController::class, 'setActive']);
        Route::get('/active', [TahunAjaranController::class, 'getActive']);
        Route::get('/{id}/kelas', [TahunAjaranController::class, 'getKelas']);
        Route::get('/{id}/jadwal', [TahunAjaranController::class, 'getJadwal']);
    });

    // User Routes
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::put('/{id}/change-password', [UserController::class, 'changePassword']);
        Route::get('/role/{roleId}', [UserController::class, 'getByRole']);
        Route::get('/search', [UserController::class, 'search']);
    });

    // Staf Routes
    Route::prefix('staf')->group(function () {
        Route::get('/', [StafController::class, 'index']);
        Route::post('/', [StafController::class, 'store']);
        Route::get('/{id}', [StafController::class, 'show']);
        Route::put('/{id}', [StafController::class, 'update']);
        Route::delete('/{id}', [StafController::class, 'destroy']);
        Route::get('/by-user/{userId}', [StafController::class, 'getByUserId']);
        Route::get('/search', [StafController::class, 'search']);
    });
});