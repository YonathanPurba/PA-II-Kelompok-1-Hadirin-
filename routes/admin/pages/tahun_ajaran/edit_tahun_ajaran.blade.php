@extends('layouts.admin-layout')

@section('title', 'Edit Data Tahun Ajaran')

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
                        <span class="fs-5 text-muted">/ Edit Tahun Ajaran</span>
                    </h1>
                    <p class="mb-2">Halaman untuk mengubah informasi tahun ajaran</p>
                </header>

                <div class="data">
                    <form action="{{ route('tahun-ajaran.update', $tahunAjaran->id_tahun_ajaran) }}" method="POST"
                        class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf
                        @method('PUT')

                        <!-- Nama Tahun Ajaran -->
                        <div class="mb-3">
                            <label for="nama_tahun_ajaran" class="form-label">Nama Tahun Ajaran</label>
                            <input type="text" name="nama_tahun_ajaran" id="nama_tahun_ajaran"
                                class="form-control @error('nama_tahun_ajaran') is-invalid @enderror"
                                value="{{ old('nama_tahun_ajaran', $tahunAjaran->nama_tahun_ajaran) }}" required>
                            @error('nama_tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                value="{{ old('tanggal_mulai', $tahunAjaran->tanggal_mulai) }}" required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai', $tahunAjaran->tanggal_selesai) }}" required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-3">
                            <label for="aktif" class="form-label">Status Aktif</label>
                            <select name="aktif" id="aktif" class="form-select @error('aktif') is-invalid @enderror">
                                <option value="1" {{ old('aktif', $tahunAjaran->aktif) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('aktif', $tahunAjaran->aktif) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
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
