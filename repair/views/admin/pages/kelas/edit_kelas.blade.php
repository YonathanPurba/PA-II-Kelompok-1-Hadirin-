@extends('layouts.admin-layout')

@section('title', 'Edit Data Kelas')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul mb-4">
                    <h1 class="mb-3">
                        <a href="{{ route('kelas.index') }}" class="text-decoration-none text-success fw-semibold">
                            Manajemen Kelas
                        </a>
                        <span class="fs-5 text-muted">/ Edit Kelas</span>
                    </h1>
                    <p class="mb-2">Halaman untuk mengubah informasi kelas</p>
                </header>

                <div class="data">
                    <form action="{{ route('kelas.update', $kelas->id_kelas) }}" method="POST"
                        class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf
                        @method('PUT')

                        <!-- Nama Kelas -->
                        <div class="mb-3">
                            <label for="nama_kelas" class="form-label">Nama Kelas</label>
                            <input type="text" name="nama_kelas" id="nama_kelas"
                                class="form-control @error('nama_kelas') is-invalid @enderror"
                                value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                            @error('nama_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tingkat -->
                        <div class="mb-3">
                            <label for="tingkat" class="form-label">Tingkat</label>
                            <input type="number" name="tingkat" id="tingkat"
                                class="form-control @error('tingkat') is-invalid @enderror"
                                value="{{ old('tingkat', $kelas->tingkat) }}" required>
                            @error('tingkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Guru Pengampu -->
                        <div class="mb-3">
                            <label for="id_guru" class="form-label">Guru Pengampu</label>
                            <select name="id_guru" id="id_guru"
                                class="form-select @error('id_guru') is-invalid @enderror" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach ($guru as $g)
                                    <option value="{{ $g->id_user }}"
                                        {{ old('id_guru', $kelas->id_guru) == $g->id_user ? 'selected' : '' }}>
                                        {{ $g->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_guru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection
