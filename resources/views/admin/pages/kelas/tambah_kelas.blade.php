@extends('layouts.admin-layout')

@section('title', 'Tambah Kelas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Kelas Baru</h3>
                    <div class="card-tools">
                        <a href="{{ route('kelas.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('kelas.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="nama_kelas">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas') }}" required>
                            @error('nama_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="tingkat">Tingkat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tingkat') is-invalid @enderror" id="tingkat" name="tingkat" value="{{ old('tingkat') }}" required>
                            @error('tingkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Contoh: 7, 8, 9, 10, 11, 12</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_guru">Wali Kelas</label>
                            <select class="form-control @error('id_guru') is-invalid @enderror" id="id_guru" name="id_guru">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id_guru }}" {{ old('id_guru') == $guru->id_guru ? 'selected' : '' }}>
                                        {{ $guru->nama_lengkap }} ({{ $guru->nip ?? 'Tanpa NIP' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_guru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="id_tahun_ajaran">Tahun Ajaran <span class="text-danger">*</span></label>
                            <select class="form-control @error('id_tahun_ajaran') is-invalid @enderror" id="id_tahun_ajaran" name="id_tahun_ajaran" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($tahunAjaranList as $tahunAjaran)
                                    <option value="{{ $tahunAjaran->id_tahun_ajaran }}" {{ old('id_tahun_ajaran') == $tahunAjaran->id_tahun_ajaran ? 'selected' : '' }}>
                                        {{ $tahunAjaran->nama_tahun_ajaran }} {{ $tahunAjaran->aktif ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Status kelas akan ditentukan berdasarkan tahun ajaran yang dipilih.</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
