<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    // Menampilkan semua data Tahun Ajaran
    public function index()
    {
        $tahunAjaran = TahunAjaran::all();
        return view('admin.pages.tahun_ajaran.manajemen_data_tahun_ajaran', compact('tahunAjaran'));
    }

    // Method untuk menampilkan form tambah tahun ajaran
    public function create()
    {
        return view('admin.pages.tahun_ajaran.tambah_tahun_ajaran');
    }

    // Method untuk menyimpan data tahun ajaran baru
    public function store(Request $request)
    {
        // Validasi data yang dimasukkan
        $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'aktif' => 'required|boolean',
        ]);

        // Menyimpan data tahun ajaran baru
        TahunAjaran::create([
            'nama_tahun_ajaran' => $request->nama_tahun_ajaran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'aktif' => $request->aktif,
            'dibuat_pada' => now(),
            // 'dibuat_oleh' => auth()->id(), // ID pengguna yang membuat
        ]);

        // Redirect ke halaman daftar tahun ajaran dengan pesan sukses
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }
    // Menampilkan detail Tahun Ajaran
    public function show(TahunAjaran $tahunAjaran)
    {
        return view('tahun_ajaran.show', compact('tahunAjaran'));
    }

    // Menampilkan form edit Tahun Ajaran
    public function edit($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        return view('admin.pages.tahun_ajaran.edit_tahun_ajaran', compact('tahunAjaran'));
    }


    // Memperbarui data Tahun Ajaran
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'aktif' => 'required|boolean',
        ]);

        $tahunAjaran = TahunAjaran::findOrFail($id);

        $tahunAjaran->update([
            'nama_tahun_ajaran' => $request->nama_tahun_ajaran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'aktif' => $request->aktif,
            'diperbarui_pada' => now(),
            // 'diperbarui_oleh' => auth()->user()->id ?? null,
        ]);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }


    // Menghapus data Tahun Ajaran
    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();

        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
