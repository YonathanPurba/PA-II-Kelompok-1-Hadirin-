<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        
        return response()->json([
            'success' => true,
            'data' => $roles,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|max:255|unique:role,role',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $role = Role::create([
            'role' => $request->role,
            'deskripsi' => $request->deskripsi,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'API',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil ditambahkan',
            'data' => $role,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Role::find($id);
        
        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $role,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        
        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'role' => 'string|max:255|unique:role,role,' . $id . ',id_role',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $roleData = $request->only(['role', 'deskripsi']);
        $roleData['diperbarui_pada'] = now();
        $roleData['diperbarui_oleh'] = 'API';
        
        $role->update($roleData);
        
        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diperbarui',
            'data' => $role,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        
        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan',
            ], 404);
        }
        
        // Cek apakah role digunakan oleh user
        $usersCount = $role->users()->count();
        if ($usersCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak dapat dihapus karena masih digunakan oleh ' . $usersCount . ' user',
            ], 400);
        }
        
        $role->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus',
        ], 200);
    }
}
