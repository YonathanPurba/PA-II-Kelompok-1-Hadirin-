<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\OrangTua;
use App\Models\TahunAjaran;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index(Request $request)
   {
       // Ambil semua data kelas untuk dropdown filter
       $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

       // Cek apakah ada filter kelas, status, dan pencarian
       $idKelas = $request->input('kelas');
       $status = $request->input('status');
       $search = $request->input('search');

       // Ambil data siswa sesuai filter (jika ada)
       $query = Siswa::with(['kelas.tahunAjaran', 'orangTua']);
       
       // Filter berdasarkan kelas
       if ($idKelas) {
           $query->where('id_kelas', $idKelas);
       }
       
       // Filter berdasarkan status
       if ($request->has('status') && $status !== '' && $status !== 'semua') {
           $query->where('status', $status);
       } elseif (!$request->has('status')) {
           // Default to active students
           $query->where('status', Siswa::STATUS_ACTIVE);
       }
       
       // Filter berdasarkan pencarian
       if ($search) {
           $query->where(function($q) use ($search) {
               $q->where('nama', 'like', "%{$search}%")
                 ->orWhere('nis', 'like', "%{$search}%");
           });
       }

       $siswaList = $query->orderBy('nama')->paginate(15);

       // Kirim data ke view
       return view('admin.pages.siswa.manajemen_data_siswa', compact('siswaList', 'kelasList'));
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
       // Ambil semua data kelas
       $kelasList = Kelas::with('tahunAjaran')
           ->whereHas('tahunAjaran', function($query) {
               $query->where('aktif', true);
           })
           ->orderBy('tingkat')
           ->orderBy('nama_kelas')
           ->get();
       
       // Ambil semua data orang tua aktif dan pending
       $orangTuaList = OrangTua::whereIn('status', [OrangTua::STATUS_ACTIVE, OrangTua::STATUS_PENDING])
           ->orderBy('nama_lengkap')
           ->get();
       
       // Ambil tahun ajaran aktif
       $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();
       
       // Tampilkan view create siswa dengan data kelas dan orang tua
       return view('admin.pages.siswa.tambah_siswa', compact('kelasList', 'orangTuaList', 'tahunAjaranAktif'));
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
           DB::beginTransaction();
           
           // Get the class and its academic year
           $kelas = Kelas::with('tahunAjaran')->find($request->id_kelas);
           
           $siswa = new Siswa();
           $siswa->nama = $request->nama;
           $siswa->nis = $request->nis;
           $siswa->jenis_kelamin = $request->jenis_kelamin;
           $siswa->id_kelas = $request->id_kelas;
           $siswa->alamat = $request->alamat;
           $siswa->tanggal_lahir = $request->tanggal_lahir;
           $siswa->id_orangtua = $request->id_orangtua;
           
           // Set tahun ajaran from the class
           if ($kelas && $kelas->tahunAjaran) {
               $siswa->id_tahun_ajaran = $kelas->tahunAjaran->id_tahun_ajaran;
               
               // Set status based on academic year's active status
               $siswa->status = $kelas->tahunAjaran->aktif ? Siswa::STATUS_ACTIVE : Siswa::STATUS_INACTIVE;
           } else {
               // Default to active if no academic year is set
               $siswa->status = Siswa::STATUS_ACTIVE;
           }
           
           $siswa->dibuat_pada = now();
           $siswa->dibuat_oleh = Auth::user()->username ?? 'system';
           $siswa->save();
           
           // Update parent status
           $orangTua = OrangTua::find($request->id_orangtua);
           if ($orangTua) {
               $orangTua->updateStatusBasedOnChildren();
           }
           
           DB::commit();

           return redirect()->route('siswa.index')
               ->with('success', 'Data siswa berhasil ditambahkan.');
       } catch (\Exception $e) {
           DB::rollBack();
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
       $siswa = Siswa::with(['kelas.tahunAjaran', 'orangTua', 'tahunAjaran'])->find($id);

       if (!$siswa) {
           return response()->json([
               'success' => false,
               'message' => 'Siswa tidak ditemukan',
           ], 404);
       }

       // Format data for response
       $data = [
           'id_siswa' => $siswa->id_siswa,
           'nama' => $siswa->nama,
           'nis' => $siswa->nis,
           'jenis_kelamin' => $siswa->jenis_kelamin,
           'alamat' => $siswa->alamat,
           'tanggal_lahir' => $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d-m-Y') : null,
           'status' => $siswa->status,
           'status_badge' => $siswa->getStatusBadgeHtml(),
           'kelas' => $siswa->kelas ? [
               'id_kelas' => $siswa->kelas->id_kelas,
               'nama_kelas' => $siswa->kelas->nama_kelas,
               'tingkat' => $siswa->kelas->tingkat,
               'tahun_ajaran' => $siswa->kelas->tahunAjaran ? $siswa->kelas->tahunAjaran->nama_tahun_ajaran : null
           ] : null,
           'orangtua' => $siswa->orangTua ? [
               'id_orangtua' => $siswa->orangTua->id_orangtua,
               'nama_lengkap' => $siswa->orangTua->nama_lengkap,
               'status' => $siswa->orangTua->status,
               'status_badge' => $siswa->orangTua->getStatusBadgeHtml()
           ] : null,
           'tahun_ajaran' => $siswa->tahunAjaran ? [
               'id_tahun_ajaran' => $siswa->tahunAjaran->id_tahun_ajaran,
               'nama_tahun_ajaran' => $siswa->tahunAjaran->nama_tahun_ajaran,
               'aktif' => $siswa->tahunAjaran->aktif
           ] : null
       ];

       return response()->json([
           'success' => true,
           'data' => $data,
       ], 200);
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit($id)
   {
       $siswa = Siswa::with(['kelas.tahunAjaran', 'orangTua', 'tahunAjaran'])->findOrFail($id);
       
       // Get all classes
       $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
       
       // Get all parents (active and pending)
       $orangTuaList = OrangTua::whereIn('status', [OrangTua::STATUS_ACTIVE, OrangTua::STATUS_PENDING])
           ->orWhere('id_orangtua', $siswa->id_orangtua) // Include current parent even if inactive
           ->orderBy('nama_lengkap')
           ->get();

       return view('admin.pages.siswa.edit_siswa', compact('siswa', 'kelasList', 'orangTuaList'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, $id)
   {
       $siswa = Siswa::findOrFail($id);
       $oldParentId = $siswa->id_orangtua;
       $oldKelasId = $siswa->id_kelas;

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
           DB::beginTransaction();
           
           // Get the class and its academic year
           $kelas = Kelas::with('tahunAjaran')->find($request->id_kelas);
           
           // Prepare update data
           $updateData = [
               'nama' => $request->nama,
               'nis' => $request->nis,
               'jenis_kelamin' => $request->jenis_kelamin,
               'id_kelas' => $request->id_kelas,
               'alamat' => $request->alamat,
               'tanggal_lahir' => $request->tanggal_lahir,
               'id_orangtua' => $request->id_orangtua,
               'diperbarui_pada' => now(),
               'diperbarui_oleh' => Auth::user()->username ?? 'system',
           ];
           
           // Update tahun ajaran if class changed
           if ($oldKelasId != $request->id_kelas && $kelas && $kelas->tahunAjaran) {
               $updateData['id_tahun_ajaran'] = $kelas->tahunAjaran->id_tahun_ajaran;
               
               // Update status based on academic year's active status
               $updateData['status'] = $kelas->tahunAjaran->aktif ? Siswa::STATUS_ACTIVE : Siswa::STATUS_INACTIVE;
           }
           
           // Allow manual status override if provided
           if ($request->has('status') && in_array($request->status, [Siswa::STATUS_ACTIVE, Siswa::STATUS_INACTIVE])) {
               $updateData['status'] = $request->status;
               // Set a flag to indicate manual status change
               $manualStatusChange = true;
           } else {
               $manualStatusChange = false;
           }
           
           $siswa->update($updateData);
           
           // Update old parent status if parent has changed
           if ($oldParentId && $oldParentId != $request->id_orangtua) {
               $oldParent = OrangTua::find($oldParentId);
               if ($oldParent) {
                   $oldParent->updateStatusBasedOnChildren();
               }
           }

           // Update new parent status only if parent changed or if this wasn't a manual status change
           if (!isset($manualStatusChange) || !$manualStatusChange || $oldParentId != $request->id_orangtua) {
               $newParent = OrangTua::find($request->id_orangtua);
               if ($newParent) {
                   $newParent->updateStatusBasedOnChildren();
               }
           }
           
           DB::commit();

           return redirect()->route('siswa.index')
               ->with('success', 'Data siswa berhasil diperbarui.');
       } catch (\Exception $e) {
           DB::rollBack();
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
           DB::beginTransaction();
           
           // Store parent ID before deleting student
           $parentId = $siswa->id_orangtua;
           
           // Delete student
           $siswa->delete();
           
           // Update parent status if parent exists
           if ($parentId) {
               $parent = OrangTua::find($parentId);
               if ($parent) {
                   $parent->updateStatusBasedOnChildren();
               }
           }
           
           DB::commit();
           
           return response()->json([
               'success' => true,
               'message' => 'Siswa berhasil dihapus',
           ], 200);
       } catch (\Exception $e) {
           DB::rollBack();
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
       $status = $request->status;
       $search = $request->search;
       
       $query = Siswa::with(['kelas', 'orangTua']);
       
       // Apply filters
       if ($kelas) {
           $query->where('id_kelas', $kelas);
       }
       
       if ($status && $status !== 'semua') {
           $query->where('status', $status);
       }
       
       if ($search) {
           $query->where(function($q) use ($search) {
               $q->where('nama', 'like', "%{$search}%")
                 ->orWhere('nis', 'like', "%{$search}%");
           });
       }
       
       $siswaList = $query->orderBy('nama')->get();

       $pdf = Pdf::loadView('exports.siswa_pdf', compact('siswaList'));
       return $pdf->download('data_siswa.pdf');
   }

   /**
    * Export data siswa to Excel.
    */
   public function exportExcel(Request $request)
   {
       return Excel::download(new SiswaExport(
           $request->kelas,
           $request->status,
           $request->search
       ), 'data_siswa.xlsx');
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
           DB::beginTransaction();
           
           Excel::import(new SiswaImport, $request->file('file'));
           
           // Update parent statuses after import
           $this->updateParentStatusesAfterImport();
           
           DB::commit();
           
           return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diimport!');
       } catch (\Exception $e) {
           DB::rollBack();
           return redirect()->route('siswa.index')->with('error', 'Gagal mengimport data: ' . $e->getMessage());
       }
   }
   
   /**
    * Update all parent statuses after import
    */
   private function updateParentStatusesAfterImport()
   {
       // Get all parents that have children
       $parents = OrangTua::whereHas('siswa')->get();
       
       foreach ($parents as $parent) {
           $parent->updateStatusBasedOnChildren();
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
   
   /**
    * Update student status based on class
    */
   public function updateStatus($id)
   {
       try {
           $siswa = Siswa::findOrFail($id);
           $siswa->updateStatusBasedOnClass();
           
           return response()->json([
               'success' => true,
               'message' => 'Status siswa berhasil diperbarui',
               'status' => $siswa->status,
               'status_badge' => $siswa->getStatusBadgeHtml()
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Gagal memperbarui status: ' . $e->getMessage()
           ], 500);
       }
   }
}
