@extends('layouts.admin-layout')

@section('title', 'Tambah Siswa')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul mb-4">
                    <h1 class="mb-3">
                        <a href="{{ route('siswa.index') }}" class="text-decoration-none text-success fw-semibold">
                            Manajemen Data Siswa
                        </a>
                        <span class="fs-5 text-muted">/ Tambah Siswa</span>
                    </h1>
                    <p class="mb-2">Halaman untuk menambahkan informasi siswa baru</p>
                </header>

                <div class="data">
                    <form action="{{ route('siswa.store') }}" method="POST"
                        class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf

                        <!-- Nama Siswa -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin"
                                class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NISN -->
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" name="nisn" id="nisn"
                                class="form-control @error('nisn') is-invalid @enderror"
                                value="{{ old('nisn') }}" required>
                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kelas -->
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select name="kelas_id" id="kelas_id"
                                class="form-select @error('kelas_id') is-invalid @enderror" required>
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id_kelas }}"
                                        {{ old('kelas_id') == $kelas->id_kelas ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3"
                                class="form-control @error('alamat') is-invalid @enderror"
                                required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection
