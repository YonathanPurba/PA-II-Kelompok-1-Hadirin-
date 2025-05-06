<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::with('role')->get();
            return view('admin.pages.users.manajemen_data_users', compact('users'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Get roles from correct table
            $roleTableName = (new Role())->getTable();
            $roles = DB::table($roleTableName)->get();
            if ($roles->isEmpty()) {
                return redirect()->route('users.index')->with('warning', 'Tidak ada data role tersedia. Silakan tambahkan role terlebih dahulu.');
            }
            return view('admin.pages.users.create', compact('roles'));
        } catch (Exception $e) {
            return redirect()->route('users.index')->with('error', 'Terjadi kesalahan saat memuat halaman: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|min:8|confirmed',
                'id_role' => 'required',
            ], [
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan, silakan pilih username lain.',
                'username.max' => 'Username maksimal 255 karakter.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'id_role.required' => 'Role wajib dipilih.',
            ]);
            
            // Check if the role exists manually
            $roleTableName = (new Role())->getTable();
            $roleExists = DB::table($roleTableName)->where('id_role', $request->id_role)->exists();
            if (!$roleExists) {
                return redirect()->back()
                    ->withErrors(['id_role' => 'Role yang dipilih tidak valid.'])
                    ->withInput();
            }

            $user = User::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'id_role' => $validated['id_role'],
                'dibuat_oleh' => Auth::user()->username,
                'dibuat_pada' => now(),
            ]);

            return redirect()->route('users.index')
                ->with('success', "User '{$user->username}' berhasil ditambahkan.");
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Gagal menambahkan user. Silakan periksa form kembali.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get the user with related data.
     */
    private function getUserWithRelations($id)
    {
        try {
            // Get the columns that actually exist in the guru table
            $guruColumns = Schema::getColumnListing('guru');
            $selectColumns = ['id_guru', 'id_user'];
            
            // Add other columns only if they exist
            foreach (['nama_lengkap', 'nip', 'alamat'] as $column) {
                if (in_array($column, $guruColumns)) {
                    $selectColumns[] = $column;
                }
            }
            
            $user = User::with(['role', 
                'guru' => function($query) use ($selectColumns) {
                    $query->select($selectColumns);
                }, 
                'orangtua', 
                'staf'
            ])->where('id_user', $id)->first();
            
            if (!$user) {
                throw new Exception('User dengan ID tersebut tidak ditemukan.');
            }
            
            return $user;
        } catch (Exception $e) {
            throw new Exception('Gagal memuat data pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->getUserWithRelations($id);
            return view('admin.pages.users.show', compact('user'));
        } catch (Exception $e) {
            return redirect()->route('admin.pages.users.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = User::findOrFail($id);
            // Get roles from correct table
            $roleTableName = (new Role())->getTable();
            $roles = DB::table($roleTableName)->get();
            
            if ($roles->isEmpty()) {
                return redirect()->route('users.index')
                    ->with('warning', 'Tidak ada data role tersedia. Silakan tambahkan role terlebih dahulu.');
            }
            
            return view('admin.pages.users.edit', compact('user', 'roles'));
        } catch (Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Pengguna tidak ditemukan atau terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function update(Request $request, string $id)
{
    try {
        $user = User::findOrFail($id);

        // Validasi hanya untuk password
        $rules = [];

        $messages = [];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
            $messages['password.min'] = 'Password minimal 8 karakter.';
            $messages['password.confirmed'] = 'Konfirmasi password tidak cocok.';
        }

        $validated = $request->validate($rules, $messages);

        $userData = [
            'diperbarui_oleh' => Auth::user()->username,
            'diperbarui_pada' => now(),
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        $message = "User '{$user->username}' berhasil diperbarui";
        if ($request->filled('password')) {
            $message .= " dengan password baru";
        }

        return redirect()->route('users.index')
            ->with('success', $message);
    } catch (ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('error', 'Gagal memperbarui user. Silakan periksa form kembali.');
    } catch (Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat memperbarui pengguna: ' . $e->getMessage());
    }
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $username = $user->username;
            
            // Check if user has related records
            if ($user->guru()->exists()) {
                return redirect()->route('users.index')
                    ->with('error', "User '{$username}' tidak dapat dihapus karena memiliki data guru terkait.");
            }
            
            if ($user->orangtua()->exists()) {
                return redirect()->route('users.index')
                    ->with('error', "User '{$username}' tidak dapat dihapus karena memiliki data orangtua terkait.");
            }
            
            if ($user->staf()->exists()) {
                return redirect()->route('users.index')
                    ->with('error', "User '{$username}' tidak dapat dihapus karena memiliki data staf terkait.");
            }
            
            // Check if trying to delete own account
            if (Auth::id() == $user->id_user) {
                return redirect()->route('users.index')
                    ->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
            }
            
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', "User '{$username}' berhasil dihapus.");
        } catch (Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat menghapus pengguna: ' . $e->getMessage());
        }
    }
}