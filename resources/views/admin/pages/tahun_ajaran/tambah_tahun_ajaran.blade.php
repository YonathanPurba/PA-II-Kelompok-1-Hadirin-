@extends('layouts.admin-layout')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Tahun Ajaran Baru</h3>
                    <div class="card-tools">
                        <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Mengaktifkan tahun ajaran baru akan menonaktifkan tahun ajaran yang sedang aktif dan mempengaruhi status kelas, siswa, dan orang tua.
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('tahun-ajaran.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="nama_tahun_ajaran">Nama Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_tahun_ajaran') is-invalid @enderror" id="nama_tahun_ajaran" name="nama_tahun_ajaran" value="{{ old('nama_tahun_ajaran') }}" required>
                            @error('nama_tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Contoh: 2025/2026</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="aktif" name="aktif" value="1" {{ old('aktif') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="aktif">Aktifkan tahun ajaran ini</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
