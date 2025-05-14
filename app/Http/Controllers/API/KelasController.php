<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
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
            'id_guru' => 'nullable|exists:guru,id_guru',
            'id_tahun_ajaran' => 'nullable|exists:tahun_ajaran,id_tahun_ajaran',
            'tingkat' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $query = Kelas::with(['guru', 'tahunAjaran']);

        if ($request->has('id_guru')) {
            $query->where('id_guru', $request->id_guru);
        }

        if ($request->has('id_tahun_ajaran')) {
            $query->where('id_tahun_ajaran', $request->id_tahun_ajaran);
        }

        if ($request->has('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $perPage = $request->input('per_page', 15);
        $kelas = $query->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->paginate($perPage);

        return $this->paginatedResponse($kelas, 'Data kelas berhasil diambil');
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
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'id_guru' => 'nullable|exists:guru,id_guru',
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $kelas = new Kelas();
        $kelas->nama_kelas = $request->nama_kelas;
        $kelas->tingkat = $request->tingkat;
        $kelas->id_guru = $request->id_guru;
        $kelas->id_tahun_ajaran = $request->id_tahun_ajaran;
        $kelas->dibuat_oleh = $request->user()->username;
        $kelas->save();

        return $this->successResponse($kelas, 'Kelas berhasil dibuat', 201);
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
            $kelas = Kelas::with(['guru', 'tahunAjaran', 'siswa'])
                ->findOrFail($id);

            return $this->successResponse($kelas, 'Data kelas berhasil diambil');
        } catch (\Exception $e) {
            return $this->errorResponse('Data kelas tidak ditemukan', 404);
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
            'nama_kelas' => 'nullable|string|max:255',
            'tingkat' => 'nullable|string|max:255',
            'id_guru' => 'nullable|exists:guru,id_guru',
            'id_tahun_ajaran' => 'nullable|exists:tahun_ajaran,id_tahun_ajaran',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        try {
            $kelas = Kelas::findOrFail($id);

            if ($request->has('nama_kelas')) {
                $kelas->nama_kelas = $request->nama_kelas;
            }

            if ($request->has('tingkat')) {
                $kelas->tingkat = $request->tingkat;
            }

            if ($request->has('id_guru')) {
                $kelas->id_guru = $request->id_guru;
            }

            if ($request->has('id_tahun_ajaran')) {
                $kelas->id_tahun_ajaran = $request->id_tahun_ajaran;
            }

            $kelas->diperbarui_oleh = $request->user()->username;
            $kelas->save();

            return $this->successResponse($kelas, 'Kelas berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->errorResponse('Data kelas tidak ditemukan', 404);
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
            $kelas = Kelas::findOrFail($id);

            // Check if there are students in this class
            if ($kelas->siswa()->count() > 0) {
                return $this->errorResponse('Kelas tidak dapat dihapus karena masih memiliki siswa', 422);
            }

            $kelas->delete();

            return $this->successResponse(null, 'Kelas berhasil dihapus');
        } catch (\Exception $e) {
            return $this->errorResponse('Data kelas tidak ditemukan', 404);
        }
    }

    public function getAllKelas()
    {
        try {
            $kelas = Kelas::select('id_kelas', 'nama_kelas')->get();

            return response()->json([
                'success' => true,
                'data' => $kelas,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }
}
