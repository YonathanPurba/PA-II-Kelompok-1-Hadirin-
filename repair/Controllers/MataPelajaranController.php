<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Models\GuruMataPelajaran;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MataPelajaranController extends Controller
{
    public function index()
    {
        // Mengambil semua mata pelajaran
        $mataPelajaran = MataPelajaran::all();

        // Menghitung jumlah guru yang mengajar tiap mata pelajaran
        foreach ($mataPelajaran as $mapel) {
            // Menghitung jumlah guru berdasarkan id_mata_pelajaran
            $mapel->jumlah_guru = GuruMataPelajaran::where('id_mata_pelajaran', $mapel->id_mata_pelajaran)->count();
        }

        // Mengirim data mata pelajaran beserta jumlah guru ke view
        return view('admin.pages.mata_pelajaran.manajemen_data_mata_pelajaran', compact('mataPelajaran'));
    }

    public function getJumlahGuru($id)
    {
        $jumlahGuru = GuruMataPelajaran::where('id_mata_pelajaran', $id)->count();

        return response()->json([
            'jumlah_guru' => $jumlahGuru
        ]);
    }

    public function getGuruPengampu($id)
    {
        $guruIDs = GuruMataPelajaran::where('id_mata_pelajaran', $id)->pluck('id_guru');
        $guruList = Guru::whereIn('id_guru', $guruIDs)->get(['id_guru', 'nama_lengkap']);

        return response()->json([
            'jumlah' => $guruList->count(),
            'data' => $guruList,
        ]);
    }

    public function show($id)
    {
        $mataPelajaran = MataPelajaran::with(['user', 'guru', 'jadwalPelajaran'])->find($id);

        if (!$mataPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $mataPelajaran,
        ], 200);
    }

    // Method untuk menampilkan form tambah mata pelajaran
    public function create()
    {
        return view('admin.pages.mata_pelajaran.tambah_mata_pelajaran');
    }

    // Method untuk menyimpan data mata pelajaran baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:10',
            'deskripsi' => 'nullable|string',
        ]);

        MataPelajaran::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
            'dibuat_pada' => now(),
            // 'dibuat_oleh' => auth()->id(), // Menyimpan ID pengguna yang membuat
        ]);

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        $semuaGuru = Guru::all(); // jika ingin ditampilkan dalam form
        return view('admin.pages.mata_pelajaran.edit_mata_pelajaran', compact('mataPelajaran', 'semuaGuru'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        // Temukan mata pelajaran berdasarkan ID
        $mataPelajaran = MataPelajaran::findOrFail($id);

        // Update data
        $mataPelajaran->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'deskripsi' => $request->deskripsi,
        ]);

        // Redirect kembali ke halaman manajemen mata pelajaran dengan pesan sukses
        return redirect()->route('mata-pelajaran.index')
            ->with('success', 'Data mata pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mataPelajaran = MataPelajaran::find($id);

        if (!$mataPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran tidak ditemukan',
            ], 404);
        }

        $mataPelajaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil dihapus',
        ], 200);
    }
}
