<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotifikasiController extends Controller
{
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|exists:users,id_user',
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
            'tipe' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $notifikasi = new Notifikasi();
        $notifikasi->id_user = $request->id_user;
        $notifikasi->judul = $request->judul;
        $notifikasi->pesan = $request->pesan;
        $notifikasi->tipe = $request->tipe ?? 'info';
        $notifikasi->dibaca = false;
        $notifikasi->dibuat_oleh = $request->user()->username;
        $notifikasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dibuat',
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