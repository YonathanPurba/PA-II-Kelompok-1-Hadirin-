@extends('layouts.admin-layout')

@section('title', 'Edit Data Orang Tua')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul mb-4">
                    <h1 class="mb-3">
                        <a href="{{ url('/orang-tua') }}" class="text-decoration-none text-success fw-semibold">
                            Manajemen Data Orang Tua
                        </a>
                        <span class="fs-5 text-muted">/ Edit Data Orang Tua</span>
                    </h1>
                    <p class="mb-2">Halaman untuk mengubah informasi orang tua</p>
                </header>

                <div class="data">
                    <form action="{{ url('orang-tua/' . $orangTua->id_orangtua) }}" method="POST"
                        class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                class="form-control @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap', $orangTua->nama_lengkap) }}" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Pengguna -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Pengguna</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $orangTua->user->username ?? '') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $orangTua->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pekerjaan -->
                        <div class="mb-3">
                            <label for="pekerjaan" class="form-label">Pekerjaan</label>
                            <input type="text" name="pekerjaan" id="pekerjaan"
                                class="form-control @error('pekerjaan') is-invalid @enderror"
                                value="{{ old('pekerjaan', $orangTua->pekerjaan) }}">
                            @error('pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon"
                                class="form-control @error('nomor_telepon') is-invalid @enderror"
                                value="{{ old('nomor_telepon', $orangTua->user->nomor_telepon ?? '') }}">
                            @error('nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $orangTua->user->email ?? '') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/orang-tua') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection
