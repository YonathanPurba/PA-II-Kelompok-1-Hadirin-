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
                    <form action="{{ url('/guru') }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm" id="formTambahGuru">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Informasi Guru</h5>
                            </div>
                            
                            <!-- Nama Lengkap -->
                            <div class="col-md-6 mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap"
                                    class="form-control @error('nama_lengkap') is-invalid @enderror"
                                    value="{{ old('nama_lengkap') }}" required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NIP -->
                            <div class="col-md-6 mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" name="nip" id="nip"
                                    class="form-control @error('nip') is-invalid @enderror" 
                                    value="{{ old('nip') }}"
                                    maxlength="18"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <small class="text-muted">NIP harus 18 digit angka</small>
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div class="col-md-6 mb-3">
                                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                <input type="tel" name="nomor_telepon" id="nomor_telepon"
                                    class="form-control @error('nomor_telepon') is-invalid @enderror"
                                    value="{{ old('nomor_telepon') }}" 
                                    maxlength="15"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <small class="text-muted">Nomor telepon harus 10-15 digit angka</small>
                                @error('nomor_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bidang Studi -->
                            <div class="col-md-6 mb-3">
                                <label for="bidang_studi" class="form-label">Bidang Studi</label>
                                <input type="text" name="bidang_studi" id="bidang_studi"
                                    class="form-control @error('bidang_studi') is-invalid @enderror"
                                    value="{{ old('bidang_studi') }}">
                                @error('bidang_studi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mata Pelajaran -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Mata Pelajaran yang Diampu</label>
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2">
                                    @foreach ($allMataPelajaran as $mapel)
                                        <div class="col">
                                            <div class="border rounded px-3 py-2 h-100 bg-light d-flex align-items-center">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" name="mata_pelajaran[]"
                                                        id="mapel-{{ $mapel->id_mata_pelajaran }}"
                                                        value="{{ $mapel->id_mata_pelajaran }}"
                                                        {{ in_array($mapel->id_mata_pelajaran, old('mata_pelajaran', [])) ? 'checked' : '' }}>
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
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Informasi Akun</h5>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Akun ini akan digunakan guru untuk login ke sistem.
                                </div>
                            </div>
                            
                            <!-- Username -->
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username') }}" required minlength="6">
                                <small class="text-muted">Username harus unik dan minimal 6 karakter</small>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" 
                                    minlength="8" required>
                                <small class="text-muted">Password minimal 8 karakter, harus mengandung huruf dan angka</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Konfirmasi Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror" 
                                    minlength="8" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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

@section('js')
<script>
    $(document).ready(function() {
        // Form validation
        $('#formTambahGuru').on('submit', function(e) {
            let isValid = true;
            const password = $('#password').val();
            const confirmPassword = $('#password_confirmation').val();
            const nip = $('#nip').val();
            const username = $('#username').val();
            
            // Password validation
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                isValid = false;
            }
            
            // Password complexity check
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d).+$/;
            if (password.length < 8 || !passwordRegex.test(password)) {
                e.preventDefault();
                alert('Password harus minimal 8 karakter dan mengandung huruf dan angka!');
                isValid = false;
            }
            
            // NIP validation
            if (nip && nip.length !== 18) {
                e.preventDefault();
                alert('NIP harus terdiri dari 18 digit!');
                isValid = false;
            }
            
            // Username validation
            if (username.length < 6) {
                e.preventDefault();
                alert('Username harus minimal 6 karakter!');
                isValid = false;
            }
            
            return isValid;
        });
        
        // Input restrictions
        $('#nip').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 18) {
                this.value = this.value.slice(0, 18);
            }
        });
        
        $('#nomor_telepon').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 15) {
                this.value = this.value.slice(0, 15);
            }
        });
    });
</script>
@endsection
