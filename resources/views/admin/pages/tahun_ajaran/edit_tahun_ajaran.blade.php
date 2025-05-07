@extends('layouts.admin-layout')

@section('title', 'Edit Tahun Ajaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Tahun Ajaran</h3>
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
                                <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Mengubah status aktif tahun ajaran akan mempengaruhi status kelas, siswa, dan orang tua.
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('tahun-ajaran.update', $tahunAjaran->id_tahun_ajaran) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="nama_tahun_ajaran">Nama Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_tahun_ajaran') is-invalid @enderror" id="nama_tahun_ajaran" name="nama_tahun_ajaran" value="{{ old('nama_tahun_ajaran', $tahunAjaran->nama_tahun_ajaran) }}" required>
                            @error('nama_tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $tahunAjaran->tanggal_mulai->format('Y-m-d')) }}" required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $tahunAjaran->tanggal_selesai->format('Y-m-d')) }}" required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="aktif" name="aktif" value="1" {{ old('aktif', $tahunAjaran->aktif) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="aktif">Aktifkan tahun ajaran ini</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
