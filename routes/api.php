<?php
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\API\AbsensiController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\JadwalController;
use App\Http\Controllers\API\NotifikasiController;
use App\Http\Controllers\API\RekapAbsensiController;
use App\Http\Controllers\API\SiswaController;
use App\Http\Controllers\API\SuratIzinController;
use App\Http\Controllers\API\KelasController;
use App\Http\Controllers\API\TahunAjaranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
// Regis
Route::post("/register", action: [ApiController::class, "register"]);
// Login
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    // Mengecek Siapa yang sedang login
    Route::get('/me', [AuthController::class, 'me']);    

    // Absensi
    Route::prefix('absensi')->group(function () {
        Route::get('/', [AbsensiController::class, 'index']);
        Route::post('/', [AbsensiController::class, 'store']);
        Route::get('/{id}', [AbsensiController::class, 'show']);
        Route::put('/{id}', [AbsensiController::class, 'update']);
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
    });
    
    // Surat Izin
    Route::prefix('surat-izin')->group(function () {
        Route::get('/', [SuratIzinController::class, 'index']);
        Route::post('/', [SuratIzinController::class, 'store']);
        Route::get('/{id}', [SuratIzinController::class, 'show']);
        Route::put('/{id}', [SuratIzinController::class, 'update']);
        Route::delete('/{id}', [SuratIzinController::class, 'destroy']);
        Route::put('/{id}/status', [SuratIzinController::class, 'updateStatus']);
    });
    
    // Rekap Absensi
    Route::prefix('rekap-absensi')->group(function () {
        Route::get('/', [RekapAbsensiController::class, 'index']);
        Route::post('/generate', [RekapAbsensiController::class, 'generateRekap']);
        Route::get('/{id}', [RekapAbsensiController::class, 'show']);
        Route::get('/kelas/detail', [RekapAbsensiController::class, 'getByClass']);
        Route::get('/siswa/detail', [RekapAbsensiController::class, 'getByStudent']);
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
    });

// // Guru Routes
// Route::prefix('guru')->group(function () {
//     Route::get('/', [GuruController::class, 'index']);
//     Route::post('/', [GuruController::class, 'store']);
//     Route::get('/{id}', [GuruController::class, 'show']);
//     Route::put('/{id}', [GuruController::class, 'update']);
//     Route::delete('/{id}', [GuruController::class, 'destroy']);
    
//     // Additional useful routes
//     Route::get('/{id}/jadwal', [GuruController::class, 'getJadwal']);
//     Route::get('/{id}/mata-pelajaran', [GuruController::class, 'getMataPelajaran']);
//     Route::get('/{id}/kelas-wali', [GuruController::class, 'getKelasWali']);
//     Route::get('/search', [GuruController::class, 'search']);
// });

// // Orangtua Routes
// Route::prefix('orangtua')->group(function () {
//     Route::get('/', [OrangtuaController::class, 'index']);
//     Route::post('/', [OrangtuaController::class, 'store']);
//     Route::get('/{id}', [OrangtuaController::class, 'show']);
//     Route::put('/{id}', [OrangtuaController::class, 'update']);
//     Route::delete('/{id}', [OrangtuaController::class, 'destroy']);
    
//     // Additional useful routes
//     Route::get('/{id}/siswa', [OrangtuaController::class, 'getSiswa']);
//     Route::get('/by-user/{userId}', [OrangtuaController::class, 'getByUserId']);
//     Route::get('/search', [OrangtuaController::class, 'search']);
// });

// // Mata Pelajaran Routes
// Route::prefix('mata-pelajaran')->group(function () {
//     Route::get('/', [MataPelajaranController::class, 'index']);
//     Route::post('/', [MataPelajaranController::class, 'store']);
//     Route::get('/{id}', [MataPelajaranController::class, 'show']);
//     Route::put('/{id}', [MataPelajaranController::class, 'update']);
//     Route::delete('/{id}', [MataPelajaranController::class, 'destroy']);
    
//     // Additional useful routes
//     Route::get('/by-tingkat/{tingkat}', [MataPelajaranController::class, 'getByTingkat']);
//     Route::get('/{id}/guru', [MataPelajaranController::class, 'getGuru']);
//     Route::get('/search', [MataPelajaranController::class, 'search']);
// });

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
});


// Old
// // Public routes
// // Regis
// Route::post("/register", action: [ApiController::class, "register"]);
// Route::post('/login', [AuthController::class, 'login']);

// // Protected routes
// Route::middleware('auth:sanctum')->group(function () {
//     // Auth
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/me', action: [AuthController::class, 'me']);
    
//     // Absensi
//     Route::get('/absensi', [AbsensiController::class, 'index']);
//     Route::post('/absensi', [AbsensiController::class, 'store']);
//     Route::get('/absensi/{id}', [AbsensiController::class, 'show']);
//     Route::put('/absensi/{id}', [AbsensiController::class, 'update']);
//     Route::get('/absensi/siswa', [AbsensiController::class, 'getByStudent']);
//     Route::get('/absensi/kelas', [AbsensiController::class, 'getByClass']);
//     Route::get('/absensi/summary', [AbsensiController::class, 'getSummary']);
    
//     // Jadwal
//     Route::get('/jadwal', [JadwalController::class, 'index']);
//     Route::post('/jadwal', [JadwalController::class, 'store']);
//     Route::get('/jadwal/{id}', [JadwalController::class, 'show']);
//     Route::put('/jadwal/{id}', [JadwalController::class, 'update']);
//     Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy']);
//     Route::get('/jadwal/guru', [JadwalController::class, 'getByTeacher']);
//     Route::get('/jadwal/kelas', [JadwalController::class, 'getByClass']);
//     Route::get('/jadwal/today', [JadwalController::class, 'getTodaySchedule']);
    
//     // Siswa
//     Route::get('/siswa', [SiswaController::class, 'index']);
//     Route::post('/siswa', [SiswaController::class, 'store']);
//     Route::get('/siswa/{id}', [SiswaController::class, 'show']);
//     Route::put('/siswa/{id}', [SiswaController::class, 'update']);
//     Route::delete('/siswa/{id}', [SiswaController::class, 'destroy']);
//     Route::get('/siswa/orangtua', [SiswaController::class, 'getByParent']);
    
//     // Surat Izin
//     Route::get('/surat-izin', [SuratIzinController::class, 'index']);
//     Route::post('/surat-izin', [SuratIzinController::class, 'store']);
//     Route::get('/surat-izin/{id}', [SuratIzinController::class, 'show']);
//     Route::put('/surat-izin/{id}', [SuratIzinController::class, 'update']);
//     Route::delete('/surat-izin/{id}', [SuratIzinController::class, 'destroy']);
//     Route::put('/surat-izin/{id}/status', [SuratIzinController::class, 'updateStatus']);
    
//     // Rekap Absensi
//     Route::get('/rekap-absensi', [RekapAbsensiController::class, 'index']);
//     Route::post('/rekap-absensi/generate', [RekapAbsensiController::class, 'generateRekap']);
//     Route::get('/rekap-absensi/{id}', [RekapAbsensiController::class, 'show']);
//     Route::get('/rekap-absensi/kelas', [RekapAbsensiController::class, 'getByClass']);
//     Route::get('/rekap-absensi/siswa', [RekapAbsensiController::class, 'getByStudent']);
    
//     // Notifikasi
//     Route::get('/notifikasi', [NotifikasiController::class, 'index']);
//     Route::post('/notifikasi', [NotifikasiController::class, 'store']);
//     Route::get('/notifikasi/{id}', [NotifikasiController::class, 'show']);
//     Route::put('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead']);
//     Route::put('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead']);
//     Route::get('/notifikasi/unread-count', [NotifikasiController::class, 'getUnreadCount']);
//     Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy']);
// });
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\ApiController;

// Route::post("register", [ApiController::class, "register"]);
// Route::post("login", [ApiController::class, "login"]);

// Route::group(["middleware" => ["auth:sanctum"] ], function(){

//     Route::get("profile", [ApiController::class, "profile"]);
//     Route::get("logout", [ApiController::class, "logout"]);
// });


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
