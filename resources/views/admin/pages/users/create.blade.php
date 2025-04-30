@extends('layouts.admin-layout')

@section('title', 'Tambah Pengguna')

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
                        <span class="fs-5 text-muted">/ Tambah Pengguna</span>
                    </h1>
                    <p class="mb-2">Halaman untuk menambahkan pengguna baru</p>
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
                    <form action="{{ route('users.store') }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf

                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username"
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username') }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Username harus unik dan akan digunakan untuk login.</div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Password minimal 8 karakter.</div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Masukkan kembali password untuk konfirmasi.</div>
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label for="id_role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="id_role" id="id_role" class="form-select @error('id_role') is-invalid @enderror" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id_role }}" {{ old('id_role') == $role->id_role ? 'selected' : '' }}>
                                        {{ $role->role }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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

@section('scripts')
<script>
    // Show password complexity feedback
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const feedback = document.createElement('div');
        
        // Remove existing feedbacks
        this.parentNode.querySelectorAll('.password-feedback').forEach(el => el.remove());
        
        feedback.className = 'password-feedback mt-2';
        
        if (password.length < 8) {
            feedback.innerHTML = '<small class="text-danger"><i class="bi bi-x-circle"></i> Password terlalu pendek (min. 8 karakter)</small>';
        } else {
            feedback.innerHTML = '<small class="text-success"><i class="bi bi-check-circle"></i> Panjang password mencukupi</small>';
        }
        
        this.parentNode.appendChild(feedback);
    });
    
    // Check password match
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        const feedback = document.createElement('div');
        
        // Remove existing feedbacks
        this.parentNode.querySelectorAll('.password-feedback').forEach(el => el.remove());
        
        feedback.className = 'password-feedback mt-2';
        
        if (confirmation && password !== confirmation) {
            feedback.innerHTML = '<small class="text-danger"><i class="bi bi-x-circle"></i> Password tidak cocok</small>';
        } else if (confirmation) {
            feedback.innerHTML = '<small class="text-success"><i class="bi bi-check-circle"></i> Password cocok</small>';
        }
        
        this.parentNode.appendChild(feedback);
    });
</script>
@endsection