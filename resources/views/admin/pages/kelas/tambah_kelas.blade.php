@extends('layouts.admin-layout')

@section('title', 'Tambah Kelas')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Tambah Kelas</h1>
                    <p class="mb-2">Halaman untuk menambahkan data kelas baru</p>
                </header>

                <div class="data">
                    <form action="{{ route('kelas.store') }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf

                        <!-- Nama Kelas -->
                        <div class="mb-3">
                            <label for="nama_kelas" class="form-label">Nama Kelas</label>
                            <input type="text" name="nama_kelas" id="nama_kelas" 
                                class="form-control @error('nama_kelas') is-invalid @enderror" 
                                value="{{ old('nama_kelas') }}" required>
                            @error('nama_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tingkat -->
                        <div class="mb-3">
                            <label for="tingkat" class="form-label">Tingkat</label>
                            <input type="text" name="tingkat" id="tingkat" 
                                class="form-control @error('tingkat') is-invalid @enderror" 
                                value="{{ old('tingkat') }}" required>
                            @error('tingkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pilih Guru Pengampu -->
                        <div class="mb-3">
                            <label for="id_guru" class="form-label">Guru Pengampu</label>
                            <select name="id_guru" id="id_guru" class="form-select @error('id_guru') is-invalid @enderror">
                                <option value="" selected>-- Pilih Guru Pengampu --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id_guru }}" 
                                        {{ old('id_guru') == $guru->id_guru ? 'selected' : '' }}>
                                        {{ $guru->nama_lengkap }}
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
