<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if we're using the correct table name
        $roleTableName = (new Role())->getTable();
        $users = User::with('role')->get();
        return view('admin.pages.users.manajemen_data_users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.pages.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'id_role' => 'required', // Removed exists validation temporarily
        ]);

        // Check if the role exists manually
        $roleExists = DB::table((new Role())->getTable())->where('id_role', $request->id_role)->exists();
        if (!$roleExists) {
            return redirect()->back()->withErrors(['id_role' => 'The selected role is invalid.'])->withInput();
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'id_role' => $request->id_role,
            'dibuat_oleh' => Auth::user()->username,
            'dibuat_pada' => now(),
        ]);

        return redirect()->route('admin.pages.users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Get the user with related data.
     */
    private function getUserWithRelations($id)
    {
        // Get the columns that actually exist in the guru table
        $guruColumns = Schema::getColumnListing('guru');
        $selectColumns = ['id_guru', 'id_user'];
        
        // Add other columns only if they exist
        foreach (['nama_lengkap', 'nip', 'alamat'] as $column) {
            if (in_array($column, $guruColumns)) {
                $selectColumns[] = $column;
            }
        }
        
        return User::with(['role', 
            'guru' => function($query) use ($selectColumns) {
                $query->select($selectColumns);
            }, 
            'orangtua', 
            'staf'
        ])->findOrFail($id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->getUserWithRelations($id);
        return view('admin.pages.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.pages.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id_user, 'id_user'),
            ],
            'id_role' => 'required', // Removed exists validation temporarily
        ]);

        // Check if the role exists manually
        $roleExists = DB::table((new Role())->getTable())->where('id_role', $request->id_role)->exists();
        if (!$roleExists) {
            return redirect()->back()->withErrors(['id_role' => 'The selected role is invalid.'])->withInput();
        }

        $userData = [
            'username' => $request->username,
            'id_role' => $request->id_role,
            'diperbarui_oleh' => Auth::user()->username,
            'diperbarui_pada' => now(),
        ];

        // Only update password if it's provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:6|confirmed',
            ]);
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.pages.users.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */

}