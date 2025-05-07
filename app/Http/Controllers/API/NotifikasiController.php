<?php

namespace App\Http\Controllers\API;

use App\Models\Siswa;
use App\Models\OrangTua;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NotifikasiController extends Controller
{

    // public function getFcmToken(Request $request)
    // {
    //     $request->validate([
    //         'kelas' => 'required|string',
    //         'nama_siswa' => 'required|string',
    //     ]);

    //     // Asumsikan kamu punya relasi: siswa -> orang_tua -> user (dengan fcm_token)
    //     $siswa = Siswa::where('nama', $request->nama_siswa)
    //         ->whereHas('kelas', function ($q) use ($request) {
    //             $q->where('nama_kelas', $request->kelas);
    //         })
    //         ->first();

    //     if (!$siswa || !$siswa->orangTua || !$siswa->orangTua->user) {
    //         return response()->json(['message' => 'Data tidak ditemukan'], 404);
    //     }

    //     $fcmToken = $siswa->orangTua->user->fcm_token;

    //     return response()->json(['fcm_token' => $fcmToken], 200);
    // }

    public function getFcmTokenById(Request $request)
    {
        // Validasi ID orang tua
        $request->validate([
            'id_orang_tua' => 'required|integer', // ID orang tua yang dikirim dalam request
        ]);
    
        // Mencari orang tua berdasarkan ID
        $orangTua = OrangTua::find($request->id_orang_tua);
    
        if (!$orangTua || !$orangTua->user) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
    
        // Mendapatkan token FCM dari user yang terkait dengan orang tua
        $fcmToken = $orangTua->user->fcm_token;
    
        return response()->json(['fcm_token' => $fcmToken], 200);
    }
    
    
    public function index(Request $request)
    {
        $user = $request->user();

        $notifikasi = Notifikasi::where('id_user', $user->id_user)
            ->orderBy('dibuat_pada', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id_user' => 'required|exists:users,id_user',
    //         'judul' => 'required|string|max:255',
    //         'pesan' => 'required|string',
    //         'tipe' => 'nullable|string|max:255',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation error',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $notifikasi = new Notifikasi();
    //     $notifikasi->id_user = $request->id_user;
    //     $notifikasi->judul = $request->judul;
    //     $notifikasi->pesan = $request->pesan;
    //     $notifikasi->tipe = $request->tipe ?? 'info';
    //     $notifikasi->dibaca = false;
    //     $notifikasi->dibuat_oleh = $request->user()->username;
    //     $notifikasi->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Notifikasi berhasil dibuat',
    //         'data' => $notifikasi
    //     ], 201);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
            'tipe' => 'required|string',
            'dibaca' => 'required|boolean',
            'dibuat_oleh' => 'required|string|max:255',
            'diperbarui_oleh' => 'required|string|max:255',
        ]);

        $notifikasi = Notifikasi::create([
            'id_user' => $request->id_user,
            'judul' => $request->judul,
            'pesan' => $request->pesan,
            'tipe' => $request->tipe,
            'dibaca' => $request->dibaca,
            'dibuat_oleh' => $request->dibuat_oleh,
            'diperbarui_oleh' => $request->diperbarui_oleh,
        ]);

        return response()->json([
            'message' => 'Notifikasi berhasil dikirim',
            'data' => $notifikasi
        ], 201);
    }


    public function show($id)
    {
        $user = request()->user();

        $notifikasi = Notifikasi::where('id_notifikasi', $id)
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();

        $notifikasi = Notifikasi::where('id_notifikasi', $id)
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        $notifikasi->dibaca = true;
        $notifikasi->waktu_dibaca = now();
        $notifikasi->diperbarui_oleh = $user->username;
        $notifikasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai dibaca',
            'data' => $notifikasi
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        Notifikasi::where('id_user', $user->id_user)
            ->where('dibaca', false)
            ->update([
                'dibaca' => true,
                'waktu_dibaca' => now(),
                'diperbarui_oleh' => $user->username
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sebagai dibaca'
        ]);
    }

    public function getUnreadCount(Request $request)
    {
        $user = $request->user();

        $count = Notifikasi::where('id_user', $user->id_user)
            ->where('dibaca', false)
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    public function destroy($id)
    {
        $user = request()->user();

        $notifikasi = Notifikasi::where('id_notifikasi', $id)
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        $notifikasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus'
        ]);
    }
}
