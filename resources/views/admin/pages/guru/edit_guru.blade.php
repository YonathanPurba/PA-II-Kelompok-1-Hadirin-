@extends('layouts.admin-layout')

@section('title', 'Edit Data Guru')

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
                        <span class="fs-5 text-muted">/ Edit Data Guru</span>
                    </h1>
                    <p class="mb-2">Halaman untuk mengubah informasi guru</p>
                </header>

                <div class="data">
                    <form action="{{ url('guru/' . $guru->id_guru) }}" method="POST"
                        class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                class="form-control @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NIP -->
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" name="nip" id="nip"
                                class="form-control @error('nip') is-invalid @enderror"
                                value="{{ old('nip', $guru->nip) }}">
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mata Pelajaran -->
                        <div class="mb-3">
                            <label class="form-label">Mata Pelajaran yang Diampu</label>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2">
                                @foreach ($allMataPelajaran as $mapel)
                                    <div class="col">
                                        <div class="border rounded px-3 py-2 h-100 bg-light d-flex align-items-center">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox" name="mata_pelajaran[]"
                                                    id="mapel-{{ $mapel->id_mata_pelajaran }}"
                                                    value="{{ $mapel->id_mata_pelajaran }}"
                                                    {{ in_array($mapel->id_mata_pelajaran, $guru->mataPelajaran->pluck('id_mata_pelajaran')->toArray()) ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2"
                                                    for="mapel-{{ $mapel->id_mata_pelajaran }}">
                                                    {{ $mapel->nama }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('mata_pelajaran')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Nomor Telepon -->
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon"
                                class="form-control @error('nomor_telepon') is-invalid @enderror"
                                value="{{ old('nomor_telepon', $guru->nomor_telepon) }}">
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


