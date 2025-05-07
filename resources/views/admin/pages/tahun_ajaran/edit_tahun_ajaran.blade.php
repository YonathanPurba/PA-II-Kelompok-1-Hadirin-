@extends('layouts.admin-layout')

@section('title', 'Edit Tahun Ajaran')

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
                    <span class="fs-5 text-muted">/ Edit Tahun Ajaran</span>
                </h1>
                <p class="mb-2">Halaman untuk mengubah informasi tahun ajaran</p>
            </header>

            <div class="data">
                <!-- Informasi Status -->
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Perhatian:</strong> Mengubah status aktif tahun ajaran akan mempengaruhi status kelas, siswa, dan orang tua.
                    <ul class="mb-0 mt-2">
                        <li>Mengaktifkan tahun ajaran ini akan menonaktifkan tahun ajaran lainnya</li>
                        <li>Semua kelas pada tahun ajaran aktif akan memiliki status "Aktif"</li>
                        <li>Semua siswa pada kelas-kelas tersebut akan memiliki status "Aktif"</li>
                        <li>Siswa pada kelas-kelas tahun ajaran nonaktif akan memiliki status "Nonaktif"</li>
                    </ul>
                </div>
                
                <form action="{{ route('tahun-ajaran.update', $tahunAjaran->id_tahun_ajaran) }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Nama Tahun Ajaran -->
                        <div class="col-md-12 mb-3">
                            <label for="nama_tahun_ajaran" class="form-label">Nama Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_tahun_ajaran') is-invalid @enderror" id="nama_tahun_ajaran" name="nama_tahun_ajaran" value="{{ old('nama_tahun_ajaran', $tahunAjaran->nama_tahun_ajaran) }}" required>
                            @error('nama_tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Tanggal Mulai -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $tahunAjaran->tanggal_mulai->format('Y-m-d')) }}" required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Tanggal Selesai -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $tahunAjaran->tanggal_selesai->format('Y-m-d')) }}" required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Status Aktif -->
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="aktif" name="aktif" value="1" {{ old('aktif', $tahunAjaran->aktif) ? 'checked' : '' }}>
                                <label class="form-check-label" for="aktif">
                                    <strong>Aktifkan tahun ajaran ini</strong>
                                    @if(!$tahunAjaran->aktif)
                                        <span class="text-danger">(Akan menonaktifkan tahun ajaran lainnya)</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Status Saat Ini -->
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i> <strong>Status Saat Ini:</strong>
                        @if($tahunAjaran->aktif)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Non-Aktif</span>
                        @endif
                        <p class="mb-0 mt-2">
                            Jumlah kelas: <span class="badge bg-info">{{ $tahunAjaran->kelas->count() }}</span>
                            Jumlah siswa: <span class="badge bg-info">{{ $tahunAjaran->siswa->count() }}</span>
                        </p>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
