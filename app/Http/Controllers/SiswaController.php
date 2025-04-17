<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{

    public function exportPdf(Request $request)
    {
        $kelas = $request->kelas;
        $siswaList = Siswa::when($kelas, function ($query, $kelas) {
            return $query->where('id_kelas', $kelas);
        })->get();

        $pdf = Pdf::loadView('exports.siswa_pdf', compact('siswaList'));
        return $pdf->download('data_siswa.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new SiswaExport($request->kelas), 'data_siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diimport!');
    }

    public function index(Request $request)
    {
        // Ambil semua data kelas untuk dropdown filter
        $kelasList = Kelas::all();

        // Cek apakah ada filter kelas
        $idKelas = $request->input('kelas');

        // Ambil data siswa sesuai filter (jika ada)
        $siswaList = Siswa::with('kelas')
            ->when($idKelas, function ($query) use ($idKelas) {
                return $query->where('id_kelas', $idKelas);
            })
            ->get();

        // Kirim data ke view
        return view('admin.pages.siswa.manajemen_data_siswa', compact('siswaList', 'kelasList'));
    }

    public function show($id)
    {
        $siswa = Siswa::with(['user', 'kelas', 'orangTua', 'absensi'])->find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }

    public function create()
    {
        // Ambil semua data kelas
        $kelasList = Kelas::all();

        // Tampilkan view create siswa dengan data kelas
        return view('admin.pages.siswa.tambah_siswa', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string|max:10|unique:siswa',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas_id' => 'nullable|exists:kelas,id_kelas',
            'id_user' => 'nullable|exists:users,id_user',
            'id_orang_tua' => 'nullable|exists:orang_tua,id_orang_tua',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $siswa = Siswa::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil ditambahkan',
            'data' => $siswa,
        ], 201);
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelasList = Kelas::all();

        return view('admin.pages.siswa.edit_siswa', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'string|max:255',
            'nisn' => 'string|max:10|unique:siswa,nisn,' . $id . ',id_siswa',
            'jenis_kelamin' => 'in:Laki-laki,Perempuan',
            'kelas_id' => 'nullable|exists:kelas,id_kelas',
            'id_user' => 'nullable|exists:users,id_user',
            'id_orang_tua' => 'nullable|exists:orang_tua,id_orang_tua',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $siswa->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil diperbarui',
            'data' => $siswa,
        ], 200);
    }

    public function destroy($id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan',
            ], 404);
        }

        $siswa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dihapus',
        ], 200);
    }

    public function getByKelas($kelasId)
    {
        $siswa = Siswa::with(['user', 'orangTua'])
            ->where('kelas_id', $kelasId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }

    public function getByOrangTua($orangTuaId)
    {
        $siswa = Siswa::with(['kelas'])
            ->where('id_orang_tua', $orangTuaId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }
}
