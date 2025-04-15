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

    // Menampilkan form tambah Tahun Ajaran
    public function create()
    {
        return view('tahun_ajaran.create');
    }

    // Menyimpan data Tahun Ajaran
    public function store(Request $request)
    {
        $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        TahunAjaran::create($request->all());

        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    // Menampilkan detail Tahun Ajaran
    public function show(TahunAjaran $tahunAjaran)
    {
        return view('tahun_ajaran.show', compact('tahunAjaran'));
    }

    // Menampilkan form edit Tahun Ajaran
    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('tahun_ajaran.edit', compact('tahunAjaran'));
    }

    // Memperbarui data Tahun Ajaran
    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'nama_tahun_ajaran' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        $tahunAjaran->update($request->all());

        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    // Menghapus data Tahun Ajaran
    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();

        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
