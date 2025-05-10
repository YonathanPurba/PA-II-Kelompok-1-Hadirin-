<?php

namespace App\Http\Controllers\API;

use App\Models\OrangTua;
use App\Models\SuratIzin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SuratIzinController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'nullable|exists:siswa,id_siswa',
            'id_orangtua' => 'nullable|exists:orangtua,id_orangtua',
            'status' => 'nullable|in:menunggu,disetujui,ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = SuratIzin::with(['siswa', 'orangtua']);

        if ($request->has('id_siswa')) {
            $query->where('id_siswa', $request->id_siswa);
        }

        if ($request->has('id_orangtua')) {
            $query->where('id_orangtua', $request->id_orangtua);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $suratIzin = $query->orderBy('dibuat_pada', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $suratIzin
        ]);
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id_siswa' => 'required|exists:siswa,id_siswa',
    //         'id_orangtua' => 'required|exists:orangtua,id_orangtua',
    //         'jenis' => 'required|in:sakit,izin',
    //         'tanggal_mulai' => 'required|date',
    //         'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    //         'alasan' => 'required|string',
    //         'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation error',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $suratIzin = new SuratIzin();
    //     $suratIzin->id_siswa = $request->id_siswa;
    //     $suratIzin->id_orangtua = $request->id_orangtua;
    //     $suratIzin->jenis = $request->jenis;
    //     $suratIzin->tanggal_mulai = $request->tanggal_mulai;
    //     $suratIzin->tanggal_selesai = $request->tanggal_selesai;
    //     $suratIzin->alasan = $request->alasan;
    //     $suratIzin->status = 'menunggu';
    //     $suratIzin->dibuat_oleh = $request->user()->username;

    //     if ($request->hasFile('file_lampiran')) {
    //         $file = $request->file('file_lampiran');
    //         $fileName = time() . '_' . $file->getClientOriginalName();
    //         $filePath = $file->storeAs('surat_izin', $fileName, 'public');
    //         $suratIzin->file_lampiran = $filePath;
    //     }

    //     $suratIzin->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Surat izin berhasil dibuat',
    //         'data' => $suratIzin
    //     ], 201);
    // }

    public function store(Request $request)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|exists:users,id_user',
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'jenis' => 'required|in:sakit,izin',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari orangtua berdasarkan id_user
        $orangtua = OrangTua::where('id_user', $request->id_user)->first();

        if (!$orangtua) {
            return response()->json([
                'success' => false,
                'message' => 'Data orang tua tidak ditemukan untuk user ini'
            ], 404);
        }

        // Handle file upload jika ada
        $fileName = null;
        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $fileName = $file->store('lampiran_surat_izin', 'public');
        }

        // Simpan surat izin
        $surat = SuratIzin::create([
            'id_siswa' => $request->id_siswa,
            'id_orangtua' => $orangtua->id_orangtua,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'file_lampiran' => $fileName,
            'status' => 'menunggu',
            'dibuat_oleh' => $request->id_user,
            'diperbarui_oleh' => $request->id_user,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Surat izin berhasil disimpan',
            'data' => $surat
        ]);
    }
    public function show($id)
    {
        $suratIzin = SuratIzin::with(['siswa', 'orangtua'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $suratIzin
        ]);
    }

    public function update(Request $request, $id)
    {
        $suratIzin = SuratIzin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'jenis' => 'nullable|in:sakit,izin',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'alasan' => 'nullable|string',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:menunggu,disetujui,ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('jenis')) {
            $suratIzin->jenis = $request->jenis;
        }

        if ($request->has('tanggal_mulai')) {
            $suratIzin->tanggal_mulai = $request->tanggal_mulai;
        }

        if ($request->has('tanggal_selesai')) {
            $suratIzin->tanggal_selesai = $request->tanggal_selesai;
        }

        if ($request->has('alasan')) {
            $suratIzin->alasan = $request->alasan;
        }

        if ($request->has('status')) {
            $suratIzin->status = $request->status;
        }

        if ($request->hasFile('file_lampiran')) {
            // Delete old file if exists
            if ($suratIzin->file_lampiran) {
                Storage::disk('public')->delete($suratIzin->file_lampiran);
            }

            $file = $request->file('file_lampiran');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat_izin', $fileName, 'public');
            $suratIzin->file_lampiran = $filePath;
        }

        $suratIzin->diperbarui_oleh = $request->user()->username;
        $suratIzin->save();

        return response()->json([
            'success' => true,
            'message' => 'Surat izin berhasil diperbarui',
            'data' => $suratIzin
        ]);
    }

    public function destroy($id)
    {
        $suratIzin = SuratIzin::findOrFail($id);

        // Delete file if exists
        if ($suratIzin->file_lampiran) {
            Storage::disk('public')->delete($suratIzin->file_lampiran);
        }

        $suratIzin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Surat izin berhasil dihapus'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:menunggu,disetujui,ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $suratIzin = SuratIzin::findOrFail($id);
        $suratIzin->status = $request->status;
        $suratIzin->diperbarui_oleh = $request->user()->username;
        $suratIzin->save();

        return response()->json([
            'success' => true,
            'message' => 'Status surat izin berhasil diperbarui',
            'data' => $suratIzin
        ]);
    }

    public function viewFile($fileName)
    {
        $filePath = public_path("storage/lampiran_surat_izin/{$fileName}");

        // Cek apakah file ada di lokasi yang diinginkan
        if (!file_exists($filePath)) {
            return response()->json([
                'error' => 'File not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Mengembalikan file dengan header yang sesuai
        return response()->file($filePath);
    }
}
