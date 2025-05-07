<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    /**
     * Display a listing of all classes.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $tingkat = $request->input('tingkat');
        $tahunAjaran = $request->input('tahun_ajaran');
        $search = $request->input('search');
        
        // Build query with relationships
        $query = Kelas::with(['guru', 'tahunAjaran', 'siswa']);
        
        // Apply filters
        if ($tingkat) {
            $query->where('tingkat', $tingkat);
        }
        
        if ($tahunAjaran) {
            $query->where('id_tahun_ajaran', $tahunAjaran);
        }
        
        if ($search) {
            $query->where('nama_kelas', 'like', "%{$search}%");
        }
        
        // Get data
        $kelas = $query->orderBy('tingkat')->orderBy('nama_kelas')->get();
        
        // Get list of academic years for filter dropdown
        $tahunAjaranList = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')->get();
        
        // Get unique grade levels for filter dropdown
        $tingkatList = Kelas::select('tingkat')->distinct()->orderBy('tingkat')->pluck('tingkat');

        // Return view with data
        return view('admin.pages.kelas.manajemen_data_kelas', compact('kelas', 'tahunAjaranList', 'tingkatList'));
    }

    /**
     * Show the form for creating a new class.
     */
    public function create()
    {
        // Get teachers for dropdown
        $gurus = Guru::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        
        // Get academic years for dropdown, with active one selected by default
        $tahunAjaranList = TahunAjaran::orderBy('aktif', 'desc')->orderBy('nama_tahun_ajaran', 'desc')->get();
        
        return view('admin.pages.kelas.tambah_kelas', compact('gurus', 'tahunAjaranList'));
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'id_guru' => 'nullable|exists:guru,id_guru',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
        ]);

        DB::beginTransaction();
        try {
            // Create new class
            $kelas = Kelas::create([
                'nama_kelas' => $validated['nama_kelas'],
                'tingkat' => $validated['tingkat'],
                'id_guru' => $validated['id_guru'],
                'id_tahun_ajaran' => $validated['id_tahun_ajaran'],
                'dibuat_pada' => now(),
                'dibuat_oleh' => Auth::user()->username ?? 'system',
            ]);
            
            DB::commit();
            
            return redirect()->route('kelas.index')
                ->with('success', 'Kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan kelas: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified class.
     */
    public function show($id)
    {
        $kelas = Kelas::with(['guru', 'tahunAjaran', 'siswa'])->findOrFail($id);
        
        return view('admin.pages.kelas.detail_kelas', compact('kelas'));
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $gurus = Guru::orderBy('nama_lengkap')->get();
        $tahunAjaranList = TahunAjaran::orderBy('aktif', 'desc')->orderBy('nama_tahun_ajaran', 'desc')->get();

        return view('admin.pages.kelas.edit_kelas', compact('kelas', 'gurus', 'tahunAjaranList'));
    }

    /**
     * Update the specified class in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'id_guru' => 'nullable|exists:guru,id_guru',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
        ]);

        DB::beginTransaction();
        try {
            $kelas = Kelas::findOrFail($id);
            $oldTahunAjaranId = $kelas->id_tahun_ajaran;
            
            $kelas->update([
                'nama_kelas' => $request->nama_kelas,
                'tingkat' => $request->tingkat,
                'id_guru' => $request->id_guru,
                'id_tahun_ajaran' => $request->id_tahun_ajaran,
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => Auth::user()->username ?? 'system',
            ]);
            
            // If academic year has changed, update student statuses
            if ($oldTahunAjaranId != $request->id_tahun_ajaran) {
                $kelas->updateStudentsStatus();
            }
            
            DB::commit();

            return redirect()->route('kelas.index')
                ->with('success', 'Data kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data kelas: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);
            
            // Check if class has students
            if ($kelas->siswa->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak dapat dihapus karena masih memiliki siswa.',
                ], 400);
            }
            
            $kelas->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dihapus.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kelas: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Get students in a class.
     */
    public function getStudents($id)
    {
        $kelas = Kelas::with(['siswa' => function($query) {
            $query->orderBy('nama');
        }])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $kelas->siswa,
        ], 200);
    }
    
    /**
     * Update all student statuses in a class.
     */
    public function updateStudentStatuses($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);
            $kelas->updateStudentsStatus();
            
            return response()->json([
                'success' => true,
                'message' => 'Status siswa berhasil diperbarui.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status siswa: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Get classes by academic year.
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
        ], 200);
    }
    
    /**
     * Get active classes (from active academic year).
     */
    public function getActiveClasses()
    {
        $kelas = Kelas::whereHas('tahunAjaran', function($query) {
            $query->where('aktif', true);
        })
        ->orderBy('tingkat')
        ->orderBy('nama_kelas')
        ->get();
        
        return response()->json([
            'success' => true,
            'data' => $kelas,
        ], 200);
    }
}
