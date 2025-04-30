<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TahunAjaranController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aktif' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $query = TahunAjaran::query();

        if ($request->has('aktif')) {
            $query->where('aktif', $request->aktif);
        }

        $perPage = $request->input('per_page', 15);
        $tahunAjaran = $query->orderBy('tanggal_mulai', 'desc')
                            ->paginate($perPage);

        return $this->paginatedResponse($tahunAjaran, 'Data tahun ajaran berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_tahun_ajaran' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'aktif' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        DB::beginTransaction();
        try {
            $tahunAjaran = new TahunAjaran();
            $tahunAjaran->nama_tahun_ajaran = $request->nama_tahun_ajaran;
            $tahunAjaran->tanggal_mulai = $request->tanggal_mulai;
            $tahunAjaran->tanggal_selesai = $request->tanggal_selesai;
            $tahunAjaran->aktif = $request->aktif ?? false;
            $tahunAjaran->dibuat_oleh = $request->user()->username;
            
            // If this tahun ajaran is set as active, deactivate all others
            if ($tahunAjaran->aktif) {
                TahunAjaran::where('aktif', true)->update(['aktif' => false]);
            }
            
            $tahunAjaran->save();
            
            DB::commit();

            return $this->successResponse($tahunAjaran, 'Tahun ajaran berhasil dibuat', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Gagal membuat tahun ajaran: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $tahunAjaran = TahunAjaran::with(['kelas', 'jadwal'])
                                     ->findOrFail($id);

            return $this->successResponse($tahunAjaran, 'Data tahun ajaran berhasil diambil');
        } catch (\Exception $e) {
            return $this->errorResponse('Data tahun ajaran tidak ditemukan', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_tahun_ajaran' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after:tanggal_mulai',
            'aktif' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        DB::beginTransaction();
        try {
            $tahunAjaran = TahunAjaran::findOrFail($id);
            
            if ($request->has('nama_tahun_ajaran')) {
                $tahunAjaran->nama_tahun_ajaran = $request->nama_tahun_ajaran;
            }
            
            if ($request->has('tanggal_mulai')) {
                $tahunAjaran->tanggal_mulai = $request->tanggal_mulai;
            }
            
            if ($request->has('tanggal_selesai')) {
                $tahunAjaran->tanggal_selesai = $request->tanggal_selesai;
            }
            
            if ($request->has('aktif')) {
                $tahunAjaran->aktif = $request->aktif;
                
                // If this tahun ajaran is set as active, deactivate all others
                if ($tahunAjaran->aktif) {
                    TahunAjaran::where('id_tahun_ajaran', '!=', $id)
                              ->where('aktif', true)
                              ->update(['aktif' => false]);
                }
            }
            
            $tahunAjaran->diperbarui_oleh = $request->user()->username;
            $tahunAjaran->save();
            
            DB::commit();

            return $this->successResponse($tahunAjaran, 'Tahun ajaran berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Data tahun ajaran tidak ditemukan', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $tahunAjaran = TahunAjaran::findOrFail($id);
            
            // Check if there are related records
            if ($tahunAjaran->kelas()->count() > 0 || $tahunAjaran->jadwal()->count() > 0) {
                return $this->errorResponse('Tahun ajaran tidak dapat dihapus karena masih digunakan', 422);
            }
            
            // Cannot delete active tahun ajaran
            if ($tahunAjaran->aktif) {
                return $this->errorResponse('Tahun ajaran aktif tidak dapat dihapus', 422);
            }
            
            $tahunAjaran->delete();

            return $this->successResponse(null, 'Tahun ajaran berhasil dihapus');
        } catch (\Exception $e) {
            return $this->errorResponse('Data tahun ajaran tidak ditemukan', 404);
        }
    }

    /**
     * Set a tahun ajaran as active
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setActive(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $tahunAjaran = TahunAjaran::findOrFail($id);
            
            // Deactivate all tahun ajaran
            TahunAjaran::where('aktif', true)->update(['aktif' => false]);
            
            // Set this one as active
            $tahunAjaran->aktif = true;
            $tahunAjaran->diperbarui_oleh = $request->user()->username;
            $tahunAjaran->save();
            
            DB::commit();

            return $this->successResponse($tahunAjaran, 'Tahun ajaran berhasil diaktifkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Data tahun ajaran tidak ditemukan', 404);
        }
    }
}