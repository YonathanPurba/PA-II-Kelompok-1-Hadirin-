@extends('layouts.admin-layout')

@section('title', 'Tambah Data Guru')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->             
                <header class="judul mb-4">
                    <h1 class="mb-3">
                        <a href="{{ url('/guru') }}" class="text-decoration-none text-success fw-semibold">
                            Manajemen Data Guru
                        </a>
                        <span class="fs-5 text-muted">/ Tambah Data Guru</span>
                    </h1>
                    <p class="mb-2">Halaman untuk menambahkan guru baru</p>
                </header>
                <div class="data">
                    <form action="{{ url('/guru') }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                class="form-control @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap') }}" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Pengguna -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Pengguna</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}"
                                required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NIP -->
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" name="nip" id="nip"
                                class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}">
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Nomor Telepon -->
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon"
                                class="form-control @error('nomor_telepon') is-invalid @enderror"
                                value="{{ old('nomor_telepon') }}">
                            @error('nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>                      

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/guru') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection
