@extends('layouts.admin-layout')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <!-- Header Judul -->
            <header class="judul mb-4">
                <h1 class="mb-3">
                    <a href="{{ route('tahun-ajaran.index') }}" class="text-decoration-none text-success fw-semibold">
                        Manajemen Data Tahun Ajaran
                    </a>
                    <span class="fs-5 text-muted">/ Tambah Tahun Ajaran</span>
                </h1>
                <p class="mb-2">Halaman untuk menambahkan tahun ajaran baru</p>
            </header>

            <div class="data">
                <!-- Informasi Status -->
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Perhatian:</strong> Mengaktifkan tahun ajaran baru akan menonaktifkan tahun ajaran yang sedang aktif dan mempengaruhi status kelas, siswa, dan orang tua.
                    <ul class="mb-0 mt-2">
                        <li>Semua kelas pada tahun ajaran aktif akan memiliki status "Aktif"</li>
                        <li>Semua siswa pada kelas-kelas tersebut akan memiliki status "Aktif"</li>
                        <li>Siswa pada kelas-kelas tahun ajaran nonaktif akan memiliki status "Nonaktif"</li>
                    </ul>
                </div>
                
                <form action="{{ route('tahun-ajaran.store') }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                    @csrf
                    
                    <div class="row">
                        <!-- Nama Tahun Ajaran -->
                        <div class="col-md-12 mb-3">
                            <label for="nama_tahun_ajaran" class="form-label">Nama Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_tahun_ajaran') is-invalid @enderror" id="nama_tahun_ajaran" name="nama_tahun_ajaran" value="{{ old('nama_tahun_ajaran') }}" required>
                            @error('nama_tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Contoh: 2025/2026</small>
                        </div>
                        
                        <!-- Tanggal Mulai -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Tanggal Selesai -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Status Aktif -->
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="aktif" name="aktif" value="1" {{ old('aktif') ? 'checked' : '' }}>
                                <label class="form-check-label" for="aktif">
                                    <strong>Aktifkan tahun ajaran ini</strong>
                                    <span class="text-danger">(Akan menonaktifkan tahun ajaran lainnya)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
