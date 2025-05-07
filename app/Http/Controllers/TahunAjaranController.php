<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of all academic years.
     */
    public function index()
    {
        $tahunAjaran = TahunAjaran::withCount(['kelas', 'siswa'])
            ->orderBy('aktif', 'desc')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();
            
        return view('admin.pages.tahun_ajaran.manajemen_data_tahun_ajaran', compact('tahunAjaran'));
    }

    /**
     * Show the form for creating a new academic year.
     */
    public function create()
    {
        return view('admin.pages.tahun_ajaran.tambah_tahun_ajaran');
    }

    /**
     * Store a newly created academic year in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:255|unique:tahun_ajaran,nama_tahun_ajaran',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'aktif' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $aktif = $request->has('aktif') && $request->aktif == 1;
            
            // If this new academic year will be active, deactivate all others
            if ($aktif) {
                TahunAjaran::where('aktif', true)->update([
                    'aktif' => false,
                    'diperbarui_pada' => now(),
                    'diperbarui_oleh' => Auth::user()->username ?? 'system',
                ]);
            }
            
            // Create new academic year
            $tahunAjaran = TahunAjaran::create([
                'nama_tahun_ajaran' => $request->nama_tahun_ajaran,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'aktif' => $aktif,
                'dibuat_pada' => now(),
                'dibuat_oleh' => Auth::user()->username ?? 'system',
            ]);
            
            DB::commit();
            
            return redirect()->route('tahun-ajaran.index')
                ->with('success', 'Tahun ajaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan tahun ajaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified academic year.
     */
    public function show($id)
    {
        $tahunAjaran = TahunAjaran::with(['kelas.siswa'])->findOrFail($id);
        
        return view('admin.pages.tahun_ajaran.detail_tahun_ajaran', compact('tahunAjaran'));
    }

    /**
     * Show the form for editing the specified academic year.
     */
    public function edit($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        
        return view('admin.pages.tahun_ajaran.edit_tahun_ajaran', compact('tahunAjaran'));
    }

    /**
     * Update the specified academic year in storage.
     */
    public function update(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        
        $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:255|unique:tahun_ajaran,nama_tahun_ajaran,' . $id . ',id_tahun_ajaran',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'aktif' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $aktif = $request->has('aktif') && $request->aktif == 1;
            $wasActive = $tahunAjaran->aktif;
            
            // If this academic year will be active and wasn't before, deactivate all others
            if ($aktif && !$wasActive) {
                TahunAjaran::where('aktif', true)
                    ->where('id_tahun_ajaran', '!=', $id)
                    ->update([
                        'aktif' => false,
                        'diperbarui_pada' => now(),
                        'diperbarui_oleh' => Auth::user()->username ?? 'system',
                    ]);
            }
            
            // Update academic year
            $tahunAjaran->update([
                'nama_tahun_ajaran' => $request->nama_tahun_ajaran,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'aktif' => $aktif,
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => Auth::user()->username ?? 'system',
            ]);
            
            // If active status changed, update all related classes and students
            if ($wasActive != $aktif) {
                $this->updateRelatedStatuses($tahunAjaran);
            }
            
            DB::commit();
            
            return redirect()->route('tahun-ajaran.index')
                ->with('success', 'Tahun ajaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui tahun ajaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified academic year from storage.
     */
    public function destroy($id)
    {
        try {
            $tahunAjaran = TahunAjaran::withCount(['kelas', 'siswa'])->findOrFail($id);
            
            // Check if academic year has classes or students
            if ($tahunAjaran->kelas_count > 0 || $tahunAjaran->siswa_count > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tahun ajaran tidak dapat dihapus karena masih memiliki kelas atau siswa terkait.',
                ], 400);
            }
            
            // Check if it's the active academic year
            if ($tahunAjaran->aktif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tahun ajaran aktif tidak dapat dihapus. Aktifkan tahun ajaran lain terlebih dahulu.',
                ], 400);
            }
            
            $tahunAjaran->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Tahun ajaran berhasil dihapus.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tahun ajaran: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Set the specified academic year as active.
     */
    public function setActive($id)
    {
        try {
            $tahunAjaran = TahunAjaran::findOrFail($id);
            
            // If already active, do nothing
            if ($tahunAjaran->aktif) {
                return redirect()->route('tahun-ajaran.index')
                    ->with('info', 'Tahun ajaran ' . $tahunAjaran->nama_tahun_ajaran . ' sudah aktif.');
            }
            
            // Set as active (this will also update all related classes and students)
            $tahunAjaran->setAsActive();
            
            return redirect()->route('tahun-ajaran.index')
                ->with('success', 'Tahun ajaran ' . $tahunAjaran->nama_tahun_ajaran . ' berhasil diaktifkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengaktifkan tahun ajaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Update all statuses related to this academic year.
     */
    private function updateRelatedStatuses(TahunAjaran $tahunAjaran)
    {
        // Update all classes in this academic year
        foreach ($tahunAjaran->kelas as $kelas) {
            $kelas->updateStudentsStatus();
        }
        
        // Update all students directly associated with this academic year
        foreach ($tahunAjaran->siswa as $siswa) {
            $siswa->updateStatusBasedOnClass();
        }
    }
    
    /**
     * Get active academic year.
     */
    public function getActive()
    {
        $tahunAjaran = TahunAjaran::where('aktif', true)->first();
        
        if (!$tahunAjaran) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada tahun ajaran aktif.',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $tahunAjaran,
        ], 200);
    }
}
