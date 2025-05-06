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
                    <form action="{{ route('siswa.store') }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf

                        <div class="row">
                            <!-- Nama Siswa -->
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama"
                                    class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}"
                                    required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NIS -->
                            <div class="col-md-6 mb-3">
                                <label for="nis" class="form-label">NIS</label>
                                <input type="text" name="nis" id="nis"
                                    class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis') }}"
                                    required>
                                @error('nis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="col-md-6 mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin"
                                    class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kelas -->
                            <div class="col-md-6 mb-3">
                                <label for="id_kelas" class="form-label">Kelas</label>
                                <select name="id_kelas" id="id_kelas"
                                    class="form-select @error('id_kelas') is-invalid @enderror" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id_kelas }}"
                                            {{ old('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                    class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                    value="{{ old('tanggal_lahir') }}">
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Orang Tua -->
                            <div class="col-md-6 mb-3">
                                <label for="id_orangtua" class="form-label">Orang Tua <span class="text-danger">*</span></label>
                                <select name="id_orangtua" id="id_orangtua"
                                    class="form-select @error('id_orangtua') is-invalid @enderror" required>
                                    <option value="">Pilih Orang Tua</option>
                                    @foreach ($orangTuaList as $orangTua)
                                        <option value="{{ $orangTua->id_orangtua }}"
                                            {{ old('id_orangtua') == $orangTua->id_orangtua ? 'selected' : '' }}>
                                            {{ $orangTua->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Jika orang tua belum terdaftar, silakan <a href="{{ route('orang-tua.create') }}" target="_blank">tambahkan orang tua</a> terlebih dahulu.</div>
                                @error('id_orangtua')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror"
                                    >{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success px-4">Simpan</button>
                        </div>
                    </form>

                    <!-- Form Import Excel -->
                    <div class="mt-4 p-4 rounded-4 bg-white shadow-sm">
                        <h5 class="mb-3">Import Data Siswa dari Excel</h5>
                        <form action="{{ route('siswa.import.excel') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-2 align-items-center">
                                <div class="col-md-8">
                                    <input type="file" name="file" accept=".xlsx,.xls,.csv"
                                        class="form-control @error('file') is-invalid @enderror" required>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-upload me-1"></i> Import Excel
                                    </button>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ asset('template/template_import_siswa.xlsx') }}"
                                    class="text-decoration-underline small">
                                    Download Template Excel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Initialize select2 for better dropdown experience
        $('#id_kelas, #id_orangtua').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    });
</script>
@endsection
