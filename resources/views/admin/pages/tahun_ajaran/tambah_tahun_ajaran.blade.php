@extends('layouts.admin-layout')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul mb-4">
                    <h1 class="mb-3">
                        <a href="{{ url('/tahun-ajaran') }}" class="text-decoration-none text-success fw-semibold">
                            Manajemen Tahun Ajaran
                        </a>
                        <span class="fs-5 text-muted">/ Tambah Tahun Ajaran</span>
                    </h1>
                    <p class="mb-2">Halaman untuk menambahkan data tahun ajaran baru</p>
                </header>

                <div class="data">
                    <!-- Form tambah tahun ajaran -->
                    <form action="{{ url('tahun-ajaran') }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf

                        <!-- Nama Tahun Ajaran -->
                        <div class="mb-3">
                            <label for="nama_tahun_ajaran" class="form-label">Nama Tahun Ajaran</label>
                            <input type="text" name="nama_tahun_ajaran" id="nama_tahun_ajaran" class="form-control @error('nama_tahun_ajaran') is-invalid @enderror" value="{{ old('nama_tahun_ajaran') }}" required>
                            @error('nama_tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}" required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}" required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-3">
                            <label for="aktif" class="form-label">Status Aktif</label>
                            <select name="aktif" id="aktif" class="form-control @error('aktif') is-invalid @enderror" required>
                                <option value="1" {{ old('aktif') == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('aktif') == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('aktif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/tahun-ajaran') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection
