<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function index()
    {
        // Mengambil semua data kelas dengan data guru yang terkait
        $kelas = Kelas::with('guru')->get();

        // Mengirimkan data kelas ke view
        return view('admin.pages.kelas.manajemen_data_kelas', compact('kelas'));
    }


    public function show($id)
    {
        $kelas = Kelas::with(['user', 'siswa', 'jadwalPelajaran'])->find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $kelas,
        ], 200);
    }

    public function create()
    {
        // Mengambil data guru untuk ditampilkan di dropdown
        $gurus = Guru::all();
        return view('admin.pages.kelas.tambah_kelas', compact('gurus'));
    }

    public function store(Request $request)
    {
        // Validasi inputan
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'id_guru' => 'nullable|exists:guru,id_guru',
        ]);

        // Menyimpan data kelas
        Kelas::create([
            'nama_kelas' => $validated['nama_kelas'],
            'tingkat' => $validated['tingkat'],
            'id_guru' => $validated['id_guru'],
            'dibuat_pada' => now(),
            // 'dibuat_oleh' => auth()->user()->username,
        ]);

        // Redirect setelah data berhasil disimpan
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $guru = Guru::all();

        return view('admin.pages.kelas.edit_kelas', compact('kelas', 'guru'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'id_guru' => 'nullable|exists:guru,id_guru',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
            'id_guru' => $request->id_guru,
            'diperbarui_pada' => now(),
            // 'diperbarui_oleh' => auth()->user()->nama_lengkap ?? 'admin',
        ]);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan',
            ], 404);
        }

        $kelas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus',
        ], 200);
    }
}
