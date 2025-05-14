<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        try {
            $query = User::with('role');
    
            // Tambahkan filter berdasarkan input pencarian username
            if ($request->filled('search')) {
                $query->where('username', 'like', '%' . $request->search . '%');
            }
    
            // Urutkan dan paginate hasilnya
            $users = $query->orderBy('username')->get();
    
            return view('admin.pages.users.manajemen_data_users', compact('users'));
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    /**
     * Retrieve a user with related models.
     */
    private function getUserWithRelations($id)
    {
        $guruColumns = Schema::getColumnListing('guru');
        $selectColumns = ['id_guru', 'id_user'];

        foreach (['nama_lengkap', 'nip', 'alamat'] as $column) {
            if (in_array($column, $guruColumns)) {
                $selectColumns[] = $column;
            }
        }

        $user = User::with([
            'role',
            'guru' => fn($q) => $q->select($selectColumns),
            'orangtua',
            'staf'
        ])->find($id);

        if (!$user) {
            abort(404, 'User tidak ditemukan.');
        }

        return $user;
    }

    /**
     * Show the detail of a user.
     */
    public function show(string $id)
    {
        try {
            $user = $this->getUserWithRelations($id);
            return view('admin.pages.users.show', compact('user'));
        } catch (Exception $e) {
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Show form for editing a user.
     */
    public function edit(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $roles = Role::all();

            if ($roles->isEmpty()) {
                return redirect()->route('users.index')->with('warning', 'Belum ada data role tersedia.');
            }

            return view('admin.pages.users.edit', compact('user', 'roles'));
        } catch (Exception $e) {
            return redirect()->route('users.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update user data.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $rules = [];
            $messages = [];

            if ($request->filled('password')) {
                $rules['password'] = [
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
                ];
                $messages = [
                    'password.min' => 'Password minimal 8 karakter.',
                    'password.confirmed' => 'Konfirmasi password tidak cocok.',
                    'password.regex' => 'Password harus mengandung huruf dan angka.',
                ];
            }

            $validated = $request->validate($rules, $messages);

            $userData = [
                'diperbarui_oleh' => Auth::user()->username,
                'diperbarui_pada' => now(),
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            $message = "User '{$user->username}' berhasil diperbarui" . ($request->filled('password') ? " dengan password baru." : ".");

            return redirect()->route('users.index')->with('success', $message);
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput()->with('error', 'Periksa kembali input Anda.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete a user safely.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $username = $user->username;

            if ($user->guru()->exists()) {
                return redirect()->route('users.index')->with('error', "User '{$username}' memiliki data guru terkait.");
            }

            if ($user->orangtua()->exists()) {
                return redirect()->route('users.index')->with('error', "User '{$username}' memiliki data orangtua terkait.");
            }

            if ($user->staf()->exists()) {
                return redirect()->route('users.index')->with('error', "User '{$username}' memiliki data staf terkait.");
            }

            if (Auth::id() == $user->id_user) {
                return redirect()->route('users.index')->with('error', 'Anda tidak bisa menghapus akun yang sedang digunakan.');
            }

            $user->delete();
            return redirect()->route('users.index')->with('success', "User '{$username}' berhasil dihapus.");
        } catch (Exception $e) {
            return redirect()->route('users.index')->with('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
        }
    }
}
