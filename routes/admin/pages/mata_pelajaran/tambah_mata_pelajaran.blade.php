@extends('layouts.admin-layout')

@section('title', 'Tambah Data Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul mb-4">
                    <h1 class="mb-3">
                        <a href="{{ url('/mata-pelajaran') }}" class="text-decoration-none text-success fw-semibold">
                            Manajemen Data Mata Pelajaran
                        </a>
                        <span class="fs-5 text-muted">/ Tambah Mata Pelajaran</span>
                    </h1>
                    <p class="mb-2">Halaman untuk menambah mata pelajaran baru</p>
                </header>

                <div class="data">
                    <form action="{{ route('mata-pelajaran.store') }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf

                        <!-- Nama Mata Pelajaran -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Mata Pelajaran</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kode Mata Pelajaran -->
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode Mata Pelajaran</label>
                            <input type="text" name="kode" id="kode"
                                class="form-control @error('kode') is-invalid @enderror"
                                value="{{ old('kode') }}" required>
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi Mata Pelajaran -->
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('mata-pelajaran.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection
