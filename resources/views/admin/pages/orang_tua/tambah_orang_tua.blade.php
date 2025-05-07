@extends('layouts.admin-layout')

@section('title', 'Tambah Orang Tua')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul mb-4">
                    <h1 class="mb-3">
                        <a href="{{ route('orang-tua.index') }}" class="text-decoration-none text-success fw-semibold">
                            Manajemen Data Orang Tua
                        </a>
                        <span class="fs-5 text-muted">/ Tambah Orang Tua</span>
                    </h1>
                    <p class="mb-2">Halaman untuk menambahkan data orang tua baru</p>
                </header>

                <div class="data">
                    <form action="{{ route('orang-tua.store') }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm" id="formTambahOrangTua">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Informasi Orang Tua</h5>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Orang tua baru akan memiliki status <strong>Pending</strong> sampai ada siswa yang ditambahkan sebagai anaknya.
                                </div>
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

                            <!-- Pekerjaan -->
                            <div class="col-md-6 mb-3">
                                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                                <input type="text" name="pekerjaan" id="pekerjaan"
                                    class="form-control @error('pekerjaan') is-invalid @enderror" 
                                    value="{{ old('pekerjaan') }}">
                                @error('pekerjaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3"
                                    class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Informasi Akun</h5>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Akun ini akan digunakan orang tua untuk login ke sistem.
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
                                <div id="password-strength" class="mt-2"></div>
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
                                <div id="password-match" class="mt-2"></div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orang-tua.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
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
        // Input restrictions for phone number
        $('#nomor_telepon').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 15) {
                this.value = this.value.slice(0, 15);
            }
        });

        // Password strength check
        $('#password').on('input', function() {
            const password = $(this).val();
            const passwordStrength = $('#password-strength');
            
            if (password.length === 0) {
                passwordStrength.html('');
                return;
            }
            
            // Check for minimum length
            const hasMinLength = password.length >= 8;
            // Check for letters and numbers
            const hasLetters = /[A-Za-z]/.test(password);
            const hasNumbers = /[0-9]/.test(password);
            
            let strengthHtml = '';
            
            if (hasMinLength && hasLetters && hasNumbers) {
                strengthHtml = '<small class="text-success"><i class="bi bi-check-circle"></i> Password kuat</small>';
            } else {
                strengthHtml = '<small class="text-danger"><i class="bi bi-x-circle"></i> Password harus minimal 8 karakter dan mengandung huruf dan angka</small>';
            }
            
            passwordStrength.html(strengthHtml);
        });
        
        // Password match check
        $('#password_confirmation').on('input', function() {
            const password = $('#password').val();
            const confirmation = $(this).val();
            const matchIndicator = $('#password-match');
            
            if (confirmation.length === 0) {
                matchIndicator.html('');
                return;
            }
            
            if (password === confirmation) {
                matchIndicator.html('<small class="text-success"><i class="bi bi-check-circle"></i> Password cocok</small>');
            } else {
                matchIndicator.html('<small class="text-danger"><i class="bi bi-x-circle"></i> Password tidak cocok</small>');
            }
        });

        // Form validation
        $('#formTambahOrangTua').on('submit', function(e) {
            let isValid = true;
            const password = $('#password').val();
            const confirmPassword = $('#password_confirmation').val();
            const username = $('#username').val();
            const nomor_telepon = $('#nomor_telepon').val();
            
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
            
            // Username validation
            if (username.length < 6) {
                e.preventDefault();
                alert('Username harus minimal 6 karakter!');
                isValid = false;
            }
            
            // Phone number validation
            if (nomor_telepon && (nomor_telepon.length < 10 || nomor_telepon.length > 15)) {
                e.preventDefault();
                alert('Nomor telepon harus terdiri dari 10-15 digit!');
                isValid = false;
            }
            
            return isValid;
        });
    });
</script>
@endsection
