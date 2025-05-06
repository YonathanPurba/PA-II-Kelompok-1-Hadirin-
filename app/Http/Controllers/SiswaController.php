<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\OrangTua;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Ambil semua data kelas untuk dropdown filter
    $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

    // Cek apakah ada filter kelas dan status
    $idKelas = $request->input('kelas');
    $status = $request->input('status');

    // Ambil data siswa sesuai filter (jika ada)
    $siswaList = Siswa::with(['kelas', 'orangTua'])
        ->when($idKelas, function ($query) use ($idKelas) {
            return $query->where('id_kelas', $idKelas);
        })
        ->when($request->has('status'), function ($query) use ($status) {
            if ($status !== '' && $status !== 'semua') {
                return $query->where('status', $status);
            }
            // If status is 'semua', don't apply any filter
        }, function ($query) {
            // Default to 'aktif' when status parameter is not present
            return $query->where('status', 'aktif');
        })
        ->orderBy('nama')
        ->get();

    // Kirim data ke view
    return view('admin.pages.siswa.manajemen_data_siswa', compact('siswaList', 'kelasList'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua data kelas
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        
        // Ambil semua data orang tua
        $orangTuaList = OrangTua::orderBy('nama_lengkap')->get();
        
        // Tampilkan view create siswa dengan data kelas dan orang tua
        return view('admin.pages.siswa.tambah_siswa', compact('kelasList', 'orangTuaList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:siswa,nis',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'id_orangtua' => 'required|exists:orangtua,id_orangtua',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $siswa = new Siswa();
            $siswa->nama = $request->nama;
            $siswa->nis = $request->nis;
            $siswa->jenis_kelamin = $request->jenis_kelamin;
            $siswa->id_kelas = $request->id_kelas;
            $siswa->alamat = $request->alamat;
            $siswa->tanggal_lahir = $request->tanggal_lahir;
            $siswa->id_orangtua = $request->id_orangtua;
            $siswa->status = 'aktif';
            $siswa->dibuat_pada = now();
            $siswa->dibuat_oleh = Auth::user()->username ?? 'system';
            $siswa->save();

            return redirect()->route('siswa.index')
                ->with('success', 'Data siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan data siswa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $siswa = Siswa::with(['kelas', 'orangTua'])->find($id);

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $orangTuaList = OrangTua::orderBy('nama_lengkap')->get();

        return view('admin.pages.siswa.edit_siswa', compact('siswa', 'kelasList', 'orangTuaList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:siswa,nis,' . $id . ',id_siswa',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'id_orangtua' => 'required|exists:orangtua,id_orangtua',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $siswa->nama = $request->nama;
            $siswa->nis = $request->nis;
            $siswa->jenis_kelamin = $request->jenis_kelamin;
            $siswa->id_kelas = $request->id_kelas;
            $siswa->alamat = $request->alamat;
            $siswa->tanggal_lahir = $request->tanggal_lahir;
            $siswa->id_orangtua = $request->id_orangtua;
            $siswa->diperbarui_pada = now();
            $siswa->diperbarui_oleh = Auth::user()->username ?? 'system';
            $siswa->save();

            return redirect()->route('siswa.index')
                ->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data siswa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan',
            ], 404);
        }

        try {
            $siswa->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus siswa: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export data siswa to PDF.
     */
    public function exportPdf(Request $request)
    {
        $kelas = $request->kelas;
        $siswaList = Siswa::with(['kelas', 'orangTua'])
            ->when($kelas, function ($query, $kelas) {
                return $query->where('id_kelas', $kelas);
            })
            ->orderBy('nama')
            ->get();

        $pdf = Pdf::loadView('exports.siswa_pdf', compact('siswaList'));
        return $pdf->download('data_siswa.pdf');
    }

    /**
     * Export data siswa to Excel.
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new SiswaExport($request->kelas), 'data_siswa.xlsx');
    }

    /**
     * Import data siswa from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));
            return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->route('siswa.index')->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    /**
     * Get students by class.
     */
    public function getByKelas($kelasId)
    {
        $siswa = Siswa::with(['orangTua'])
            ->where('id_kelas', $kelasId)
            ->orderBy('nama')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }

    /**
     * Get students by parent.
     */
    public function getByOrangTua($orangTuaId)
    {
        $siswa = Siswa::with(['kelas'])
            ->where('id_orangtua', $orangTuaId)
            ->orderBy('nama')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }
    
    /**
     * Get students without parents or with specific parent.
     */
    public function getAvailableStudents($parentId = null)
    {
        $query = Siswa::with(['kelas'])
            ->where(function($q) use ($parentId) {
                $q->whereNull('id_orangtua')
                  ->orWhere('id_orangtua', 0);
                
                if ($parentId) {
                    $q->orWhere('id_orangtua', $parentId);
                }
            })
            ->orderBy('id_kelas')
            ->orderBy('nama');
            
        $siswa = $query->get();

        return response()->json([
            'success' => true,
            'data' => $siswa,
        ], 200);
    }
}
