<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the mata pelajaran.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $mataPelajaran = MataPelajaran::all();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Daftar mata pelajaran berhasil diambil',
                'data' => $mataPelajaran
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil daftar mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created mata pelajaran in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|max:255|unique:mata_pelajaran,kode',
                'deskripsi' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $mataPelajaran = MataPelajaran::create([
                'nama' => $request->nama,
                'kode' => $request->kode,
                'deskripsi' => $request->deskripsi
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Mata pelajaran berhasil ditambahkan',
                'data' => $mataPelajaran
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified mata pelajaran.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $mataPelajaran = MataPelajaran::findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Detail mata pelajaran berhasil diambil',
                'data' => $mataPelajaran
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified mata pelajaran in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $mataPelajaran = MataPelajaran::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'kode' => 'required|string|max:255|unique:mata_pelajaran,kode,' . $id . ',id_mata_pelajaran',
                'deskripsi' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $mataPelajaran->update([
                'nama' => $request->nama,
                'kode' => $request->kode,
                'deskripsi' => $request->deskripsi
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Mata pelajaran berhasil diperbarui',
                'data' => $mataPelajaran
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified mata pelajaran from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $mataPelajaran = MataPelajaran::findOrFail($id);
            $mataPelajaran->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Mata pelajaran berhasil dihapus'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get mata pelajaran by tingkat.
     *
     * @param  string  $tingkat
     * @return \Illuminate\Http\Response
     */
    public function getByTingkat($tingkat)
    {
        try {
            // Assuming there's a relationship or field to filter by tingkat
            // You might need to adjust this based on your actual database structure
            $mataPelajaran = MataPelajaran::where('tingkat', $tingkat)->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Daftar mata pelajaran berdasarkan tingkat berhasil diambil',
                'data' => $mataPelajaran
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil daftar mata pelajaran berdasarkan tingkat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get guru who teach this mata pelajaran.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getGuru($id)
    {
        try {
            $mataPelajaran = MataPelajaran::findOrFail($id);
            
            // Assuming there's a relationship between MataPelajaran and Guru through guru_mata_pelajaran
            $guru = Guru::whereHas('mataPelajaran', function($query) use ($id) {
                $query->where('id_mata_pelajaran', $id);
            })->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Daftar guru pengajar mata pelajaran berhasil diambil',
                'data' => [
                    'mata_pelajaran' => $mataPelajaran,
                    'guru' => $guru
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil daftar guru pengajar mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search mata pelajaran by name or code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            
            $mataPelajaran = MataPelajaran::where('nama', 'LIKE', "%{$query}%")
                ->orWhere('kode', 'LIKE', "%{$query}%")
                ->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Pencarian mata pelajaran berhasil',
                'data' => $mataPelajaran
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal melakukan pencarian mata pelajaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get mata pelajaran by kode.
     *
     * @param  string  $kode
     * @return \Illuminate\Http\Response
     */
    public function getByKode($kode)
    {
        try {
            $mataPelajaran = MataPelajaran::where('kode', $kode)->firstOrFail();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Mata pelajaran berhasil ditemukan',
                'data' => $mataPelajaran
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mata pelajaran dengan kode tersebut tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil mata pelajaran berdasarkan kode',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}