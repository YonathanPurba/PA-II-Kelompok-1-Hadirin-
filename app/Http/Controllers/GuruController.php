<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user', 'kelas')->get();

        return view('admin.pages.guru.manajemen_data_guru', compact('gurus'));
    }

    public function create()
    {
        return view('admin.pages.guru.tambah_guru');
    }

    // public function show($id)
    // {
    //     $guru = Guru::with(['user', 'mataPelajaran', 'jadwalPelajaran'])->find($id);

    //     if (!$guru) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Guru tidak ditemukan',
    //         ], 404);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => $guru,
    //     ], 200);
    // }

    public function show($id)
    {
        $guru = Guru::with([
            'user',
            'mataPelajaran',
            'jadwal.kelas',
            'jadwal.mataPelajaran',
        ])->find($id);

        if (!$guru) {
            return response()->json(['message' => 'Guru tidak ditemukan'], 404);
        }

        return response()->json($guru);
    }

    public function edit($id)
    {
        // Mengambil data guru beserta mata pelajaran yang terkait melalui pivot table
        $guru = Guru::with('mataPelajaran')->findOrFail($id);

        // Mengambil semua mata pelajaran untuk dropdown
        $allMataPelajaran = MataPelajaran::all();

        // Mengirimkan data guru dan mata pelajaran ke view edit
        return view('admin.pages.guru.edit_guru', compact('guru', 'allMataPelajaran'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'      => 'required|string|max:255',
            'nip'               => 'nullable|string|max:50|unique:guru,nip',
            'alamat'            => 'nullable|string',
            // 'jenis_kelamin'     => 'required|in:L,P',
            'password'          => 'required|string|min:6|confirmed',
            'nomor_telepon'     => 'nullable|string|max:20',
            'id_mata_pelajaran' => 'nullable|exists:mata_pelajaran,id_mata_pelajaran',
        ]);

        DB::beginTransaction();
        try {
            // Data user
            $userData = [
                'username'          => $request->nama,
                'password'          => bcrypt($request->password), // Hash password
                'id_role'           => 2, // Asumsikan 2 = guru
                'nomor_telepon'     => $request->nomor_telepon,
                'dibuat_pada'       => now(),
                // 'dibuat_oleh'       => auth()->id(),
            ];

            // Data guru
            $guruData = [
                'nama_lengkap'      => $request->nama_lengkap,
                'nip'               => $request->nip,
                'alamat'            => $request->alamat,
                // 'jenis_kelamin'     => $request->jenis_kelamin,
                // 'id_mata_pelajaran' => $request->id_mata_pelajaran,
                'dibuat_pada'       => now(),
                // 'dibuat_oleh'       => auth()->id(),
            ];

            // Gunakan UserService untuk menyimpan data
            $guru = UserService::createGuruWithUser($guruData, $userData);

            DB::commit();
            return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data guru: ' . $e->getMessage());
        }
    }

    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'nama_lengkap'    => 'required|string|max:255',
    //         'nama'            => 'required|string|max:255',
    //         'nip'             => 'nullable|string|max:100',
    //         'nomor_telepon'   => 'nullable|string|max:20',
    //     ]);

    //     // Siapkan data user & guru
    //     $userData = [
    //         'username'       => $validated['nama'],
    //         'nomor_telepon'  => $validated['nomor_telepon'],
    //     ];

    //     $guruData = [
    //         'nama_lengkap' => $validated['nama_lengkap'],
    //         'nip'          => $validated['nip'],
    //     ];

    //     try {
    //         UserService::updateGuruWithUser($id, $guruData, $userData);
    //         return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui.');
    //     } catch (\Exception $e) {
    //         return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()])->withInput();
    //     }
    // }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'nomor_telepon' => 'nullable|string|max:20',
            'mata_pelajaran' => 'required|array',
            'mata_pelajaran.*' => 'exists:mata_pelajaran,id_mata_pelajaran',
        ]);

        // Ambil data guru
        $guru = Guru::findOrFail($id);

        // Update data guru
        $guru->update([
            'nama_lengkap' => $request->nama_lengkap,
            'nip' => $request->nip,
            'nomor_telepon' => $request->nomor_telepon,
        ]);

        // Sinkronisasi relasi guru <-> mata pelajaran (pivot table)
        $guru->mataPelajaran()->sync($request->mata_pelajaran);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }
        

    public function destroy($id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        $guru->delete();

        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil dihapus',
        ], 200);
    }
}
