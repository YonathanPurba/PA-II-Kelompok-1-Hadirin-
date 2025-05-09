<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    /**
     * Tampilkan daftar kelas dengan filter.
     */
    public function index(Request $request)
    {
        $tingkat = $request->input('tingkat');
        $tahunAjaran = $request->input('tahun_ajaran');
        $search = $request->input('search');

        $query = Kelas::with(['guru', 'tahunAjaran', 'siswa']);

        if ($tingkat) {
            $query->where('tingkat', $tingkat);
        }

        if ($tahunAjaran) {
            $query->where('id_tahun_ajaran', $tahunAjaran);
        }

        if ($search) {
            $query->where('nama_kelas', 'like', "%{$search}%");
        }

        $kelas = $query->orderBy('tingkat')->orderBy('nama_kelas')->get();
        $tahunAjaranList = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();
        $tingkatList = Kelas::select('tingkat')->distinct()->orderBy('tingkat')->pluck('tingkat');

        return view('admin.pages.kelas.manajemen_data_kelas', compact('kelas', 'tahunAjaranList', 'tingkatList'));
    }

    /**
     * Form tambah kelas.
     */
    public function create()
{
    // Only get teachers who are active and not already assigned as class advisors
    $gurus = Guru::where('status', 'aktif')
        ->whereDoesntHave('kelas')  // Teachers with no class assigned
        ->orderBy('nama_lengkap')
        ->get();
        
    $tahunAjaranList = TahunAjaran::orderBy('aktif', 'desc')
        ->orderBy('nama_tahun_ajaran', 'desc')
        ->get();

    return view('admin.pages.kelas.tambah_kelas', compact('gurus', 'tahunAjaranList'));
}
    


    /**
     * Simpan kelas baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'id_guru' => 'nullable|exists:guru,id_guru',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
        ]);

        // Cek apakah guru sudah jadi wali kelas lain
        if ($request->id_guru) {
            $existing = Kelas::where('id_guru', $request->id_guru)->first();
            if ($existing) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Guru ini sudah menjadi wali kelas lain.');
            }
        }

        DB::beginTransaction();
        try {
            Kelas::create([
                'nama_kelas' => $request->nama_kelas,
                'tingkat' => $request->tingkat,
                'id_guru' => $request->id_guru,
                'id_tahun_ajaran' => $request->id_tahun_ajaran,
                'dibuat_pada' => now(),
                'dibuat_oleh' => Auth::user()->username ?? 'system',
            ]);

            DB::commit();
            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kelas: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail kelas.
     */
    public function show($id)
    {
        $kelas = Kelas::with(['guru', 'tahunAjaran', 'siswa'])->findOrFail($id);
        return view('admin.pages.kelas.detail_kelas', compact('kelas'));
    }

    /**
     * Form edit kelas.
     */
    public function edit($id)
{
    $kelas = Kelas::findOrFail($id);
    
    // Get teachers who are active and either:
    // 1. Not assigned as a class advisor to any class, or
    // 2. Currently assigned as the advisor for this specific class
    $gurus = Guru::where('status', 'aktif')
        ->where(function($query) use ($id) {
            $query->whereDoesntHave('kelas')  // Teachers with no class
                  ->orWhereHas('kelas', function($q) use ($id) {
                      $q->where('id_kelas', $id);  // Or teacher of this class
                  });
        })
        ->orderBy('nama_lengkap')
        ->get();
        
    $tahunAjaranList = TahunAjaran::orderBy('aktif', 'desc')
        ->orderBy('nama_tahun_ajaran', 'desc')
        ->get();

    return view('admin.pages.kelas.edit_kelas', compact('kelas', 'gurus', 'tahunAjaranList'));
}

    /**
     * Perbarui data kelas.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'id_guru' => 'nullable|exists:guru,id_guru',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
        ]);

        // Cek apakah guru sudah jadi wali kelas lain (selain kelas ini)
        if ($request->id_guru) {
            $existing = Kelas::where('id_guru', $request->id_guru)
                ->where('id_kelas', '!=', $id)
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Guru ini sudah menjadi wali kelas lain.');
            }
        }

        $oldTahunAjaranId = $kelas->id_tahun_ajaran;

        DB::beginTransaction();
        try {
            $kelas->update([
                'nama_kelas' => $request->nama_kelas,
                'tingkat' => $request->tingkat,
                'id_guru' => $request->id_guru,
                'id_tahun_ajaran' => $request->id_tahun_ajaran,
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => Auth::user()->username ?? 'system',
            ]);

            // Jika tahun ajaran berubah, update status siswa
            if ($oldTahunAjaranId != $request->id_tahun_ajaran) {
                $kelas->updateStudentsStatus();
            }

            DB::commit();
            return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data kelas: ' . $e->getMessage());
        }
    }

    /**
     * Hapus kelas.
     */
    public function destroy($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);

            if ($kelas->siswa()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak dapat dihapus karena masih memiliki siswa.',
                ], 400);
            }

            $kelas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kelas: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ambil data siswa dalam kelas.
     */
    public function getStudents($id)
    {
        $kelas = Kelas::with(['siswa' => function ($query) {
            $query->orderBy('nama');
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $kelas->siswa,
        ]);
    }

    /**
     * Update status siswa dalam kelas.
     */
    public function updateStudentStatuses($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);
            $kelas->updateStudentsStatus();

            return response()->json([
                'success' => true,
                'message' => 'Status siswa berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status siswa: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ambil kelas berdasarkan tahun ajaran.
     */
    public function getByTahunAjaran($tahunAjaranId)
    {
        $kelas = Kelas::where('id_tahun_ajaran', $tahunAjaranId)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $kelas,
        ]);
    }

    /**
     * Ambil semua kelas dari tahun ajaran aktif.
     */
    public function getActiveClasses()
    {
        $kelas = Kelas::whereHas('tahunAjaran', function ($query) {
            $query->where('aktif', true);
        })->orderBy('tingkat')->orderBy('nama_kelas')->get();

        return response()->json([
            'success' => true,
            'data' => $kelas,
        ]);
    }
}
