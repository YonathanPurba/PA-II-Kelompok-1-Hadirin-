@extends('layouts.admin-layout')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul mb-4">
                    <h1 class="mb-3">
                        <a href="{{ route('users.index') }}" class="text-decoration-none text-success fw-semibold">
                            Manajemen Data Pengguna
                        </a>
                        <span class="fs-5 text-muted">/ Edit Pengguna</span>
                    </h1>
                    <p class="mb-2">Halaman untuk mengubah informasi pengguna <strong>{{ $user->username }}</strong></p>
                </header>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="data">
                    <form action="{{ route('users.update', $user->id_user) }}" method="POST"
                        class="p-4 pt-1 rounded-4 bg-white shadow-sm" id="formEditUser">
                        @csrf
                        @method('PUT')

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Kosongkan jika tidak ingin mengubah password. Jika diisi, minimal 8 karakter dan harus mengandung huruf dan angka.</div>
                            <div id="password-strength" class="mt-2"></div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Masukkan kembali password baru untuk konfirmasi.</div>
                            <div id="password-match" class="mt-2"></div>
                        </div>

                        <!-- Informasi Update -->
                        <div class="mb-3 mt-4 border-top pt-3">
                            <div class="row">
                                @if($user->diperbarui_pada)
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-clock-history"></i> 
                                        Terakhir diperbarui: {{ \Carbon\Carbon::parse($user->diperbarui_pada)->format('d M Y H:i') }}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i>
                                        Oleh: {{ $user->diperbarui_oleh ?? '-' }}
                                    </small>
                                </div>
                                @else
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-clock-history"></i>
                                        Dibuat pada: {{ \Carbon\Carbon::parse($user->dibuat_pada)->format('d M Y H:i') }}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i>
                                        Oleh: {{ $user->dibuat_oleh ?? '-' }}
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
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
            
            if (confirmation.length === 0 || password.length === 0) {
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
        $('#formEditUser').on('submit', function(e) {
            const password = $('#password').val();
            const confirmation = $('#password_confirmation').val();
            
            // Skip validation if password field is empty (no password change)
            if (password.length === 0) {
                return true;
            }
            
            // Password complexity check
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d).+$/;
            if (password.length < 8 || !passwordRegex.test(password)) {
                e.preventDefault();
                alert('Password harus minimal 8 karakter dan mengandung huruf dan angka!');
                return false;
            }
            
            // Password match check
            if (password !== confirmation) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
            
            return true;
        });
    });
</script>
@endsection
