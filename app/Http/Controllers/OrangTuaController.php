<?php

namespace App\Http\Controllers;

use App\Exports\OrangTuaExport;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class OrangTuaController extends Controller
{
    /**
     * Display a listing of all parents.
     */
    public function index(Request $request)
    {
        $kelasId = $request->input('kelas');
        $status = $request->input('status');
    
        $kelasList = Kelas::all();
    
        $query = OrangTua::with(['siswa.kelas', 'user']);
    
        // Filter berdasarkan kelas anak
        if ($kelasId) {
            $query->whereHas('siswa', function ($siswaQuery) use ($kelasId) {
                $siswaQuery->where('id_kelas', $kelasId);
            });
        }
    
        // Improved status logic:
        if ($request->has('status')) {
            if ($status !== '' && $status !== 'semua') {
                $query->where('status', $status);
            }
            // If status is 'semua' or empty, don't apply any status filter
        } else {
            // Default to 'aktif' only when status parameter is not present at all
            $query->where('status', 'aktif');
        }
    
        $orangTuaList = $query->get();
    
        // Filter ulang relasi siswa jika kelas disaring
        if ($kelasId) {
            foreach ($orangTuaList as $orangTua) {
                $filteredSiswa = $orangTua->siswa->filter(function ($siswa) use ($kelasId) {
                    return $siswa->id_kelas == $kelasId;
                });
                $orangTua->setRelation('siswa', $filteredSiswa);
            }
        }
    
        return view('admin.pages.orang_tua.manajemen_data_orang_tua', compact('orangTuaList', 'kelasList'));
    }


/**
 * Export data orang tua to PDF.
 */
public function exportPdf(Request $request)
{
    $kelasId = $request->input('kelas');
    $status = $request->input('status');
    
    $query = OrangTua::with(['siswa.kelas', 'user']);
    
    // Filter berdasarkan kelas anak
    if ($kelasId) {
        $query->whereHas('siswa', function ($siswaQuery) use ($kelasId) {
            $siswaQuery->where('id_kelas', $kelasId);
        });
    }
    
    // Status logic
    if ($request->has('status')) {
        if ($status !== '' && $status !== 'semua') {
            $query->where('status', $status);
        }
    } else {
        $query->where('status', 'aktif');
    }
    
    $orangTuaList = $query->orderBy('nama_lengkap')->get();
    
    // Filter ulang relasi siswa jika kelas disaring
    if ($kelasId) {
        foreach ($orangTuaList as $orangTua) {
            $filteredSiswa = $orangTua->siswa->filter(function ($siswa) use ($kelasId) {
                return $siswa->id_kelas == $kelasId;
            });
            $orangTua->setRelation('siswa', $filteredSiswa);
        }
    }

    $pdf = Pdf::loadView('exports.orangtua_pdf', compact('orangTuaList'));
    return $pdf->download('data_orang_tua.pdf');
}

/**
 * Export data orang tua to Excel.
 */
public function exportExcel(Request $request)
{
    return Excel::download(new OrangTuaExport($request->input('kelas'), $request->input('status')), 'data_orang_tua.xlsx');
}
    /**
     * Show the form for creating a new parent.
     */
    public function create()
    {
        return view('admin.pages.orang_tua.tambah_orang_tua');
    }

    /**
     * Store a newly created parent in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:15',
        ]);

        DB::beginTransaction();
        try {
            // Create user account first
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'id_role' => 3, // Role id for parent
                'dibuat_pada' => now(),
                'dibuat_oleh' => Auth::user()->username ?? 'system',
            ]);

            // Create parent record
            $orangTua = OrangTua::create([
                'id_user' => $user->id_user,
                'nama_lengkap' => $request->nama_lengkap,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'nomor_telepon' => $request->nomor_telepon,
                'status' => 'aktif',
                'dibuat_pada' => now(),
                'dibuat_oleh' => Auth::user()->username ?? 'system',
            ]);

            DB::commit();

            return redirect()->route('orang-tua.index', ['status' => 'aktif'])
                ->with('success', 'Data orang tua berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan data orang tua: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified parent.
     */
    public function show($id)
    {
        $orangTua = OrangTua::with(['user', 'siswa.kelas'])->find($id);
        
        if (!$orangTua) {
            return redirect()->route('orang-tua.index')
                ->with('error', 'Data orang tua tidak ditemukan.');
        }
        
        return view('admin.pages.orang_tua.detail_orang_tua', compact('orangTua'));
    }

    /**
     * Show the form for editing the specified parent.
     */
    public function edit($id)
    {
        $orangTua = OrangTua::with('user', 'siswa.kelas')->findOrFail($id);
    
        return view('admin.pages.orang_tua.edit_orang_tua', compact('orangTua'));
    }

    /**
     * Update the specified parent in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:15',
            'status' => 'required|string|in:aktif,nonaktif',
        ]);

        DB::beginTransaction();
        try {
            $orangTua = OrangTua::findOrFail($id);
            $oldStatus = $orangTua->status;
        
            // Update parent data
            $orangTua->update([
                'nama_lengkap' => $request->nama_lengkap,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'nomor_telepon' => $request->nomor_telepon,
                'status' => $request->status,
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => Auth::user()->username ?? 'system',
            ]);
        
            DB::commit();
            
            // Redirect to the same status filter that was active before the update
            // unless the status was changed, then redirect to the new status
            $redirectStatus = ($oldStatus != $request->status) ? $request->status : request('status');
        
            return redirect()->route('orang-tua.index', ['status' => $redirectStatus])
                ->with('success', 'Data orang tua berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data orang tua: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified parent from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $orangTua = OrangTua::with('siswa', 'user')->findOrFail($id);
            
            // Check if parent has children
            if ($orangTua->siswa->count() > 0) {
                // Remove parent association from all children
                Siswa::where('id_orangtua', $id)
                    ->update([
                        'id_orangtua' => null,
                        'diperbarui_pada' => now(),
                        'diperbarui_oleh' => Auth::user()->username ?? 'system',
                    ]);
            }
            
            // Delete user account
            if ($orangTua->user) {
                $orangTua->user->delete();
            }
            
            // Delete parent record
            $orangTua->delete();
            
            DB::commit();
            
            return redirect()->route('orang-tua.index')
                ->with('success', 'Data orang tua berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus data orang tua: ' . $e->getMessage());
        }
    }
    
    /**
     * Display parents by class for their children.
     */
    public function showByKelas($id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);
        
        $orangTuaList = OrangTua::whereHas('siswa', function($query) use ($id_kelas) {
            $query->where('id_kelas', $id_kelas);
        })->with(['siswa' => function($query) use ($id_kelas) {
            $query->where('id_kelas', $id_kelas)->with('kelas');
        }, 'user'])->get();
        
        return view('admin.pages.orang_tua.kelas_orang_tua', compact('orangTuaList', 'kelas'));
    }

    /**
     * Get all parents for dropdown.
     */
    public function getList(Request $request)
    {
        $status = $request->input('status', 'aktif'); // Default to active parents
        
        $query = OrangTua::orderBy('nama_lengkap');
        
        // Only filter by status if it's provided and not empty
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }
        
        $orangTuaList = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $orangTuaList,
        ], 200);
    }
}
    