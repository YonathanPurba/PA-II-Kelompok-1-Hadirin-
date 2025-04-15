<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
            'id_orangtua' => 'nullable|exists:orangtua,id_orangtua',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Siswa::with(['kelas', 'orangtua']);

        if ($request->has('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->has('id_orangtua')) {
            $query->where('id_orangtua', $request->id_orangtua);
        }

        $siswa = $query->orderBy('nama')->get();

        return response()->json([
            'success' => true,
            'data' => $siswa
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:255|unique:siswa',
            'id_orangtua' => 'required|exists:orangtua,id_orangtua',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $siswa = new Siswa();
        $siswa->nama = $request->nama;
        $siswa->nis = $request->nis;
        $siswa->id_orangtua = $request->id_orangtua;
        $siswa->id_kelas = $request->id_kelas;
        $siswa->tanggal_lahir = $request->tanggal_lahir;
        $siswa->jenis_kelamin = $request->jenis_kelamin;
        $siswa->dibuat_oleh = $request->user()->username;
        $siswa->save();

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dibuat',
            'data' => $siswa
        ], 201);
    }

    public function show($id)
    {
        $siswa = Siswa::with(['kelas', 'orangtua'])
                      ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $siswa
        ]);
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'nullable|string|max:255',
            'nis' => 'nullable|string|max:255|unique:siswa,nis,' . $siswa->id_siswa . ',id_siswa',
            'id_orangtua' => 'nullable|exists:orangtua,id_orangtua',
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('nama')) {
            $siswa->nama = $request->nama;
        }
        
        if ($request->has('nis')) {
            $siswa->nis = $request->nis;
        }
        
        if ($request->has('id_orangtua')) {
            $siswa->id_orangtua = $request->id_orangtua;
        }
        
        if ($request->has('id_kelas')) {
            $siswa->id_kelas = $request->id_kelas;
        }
        
        if ($request->has('tanggal_lahir')) {
            $siswa->tanggal_lahir = $request->tanggal_lahir;
        }
        
        if ($request->has('jenis_kelamin')) {
            $siswa->jenis_kelamin = $request->jenis_kelamin;
        }
        
        $siswa->diperbarui_oleh = $request->user()->username;
        $siswa->save();

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil diperbarui',
            'data' => $siswa
        ]);
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dihapus'
        ]);
    }

    public function getByParent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_orangtua' => 'required|exists:orangtua,id_orangtua',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $siswa = Siswa::with(['kelas'])
                      ->where('id_orangtua', $request->id_orangtua)
                      ->get();

        return response()->json([
            'success' => true,
            'data' => $siswa
        ]);
    }
}