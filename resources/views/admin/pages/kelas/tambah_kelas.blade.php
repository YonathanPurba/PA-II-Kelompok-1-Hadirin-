@extends('layouts.admin-layout')

@section('title', 'Tambah Kelas')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <!-- Header Judul -->
            <header class="judul mb-4">
                <h1 class="mb-3">
                    <a href="{{ route('kelas.index') }}" class="text-decoration-none text-success fw-semibold">
                        Manajemen Data Kelas
                    </a>
                    <span class="fs-5 text-muted">/ Tambah Kelas</span>
                </h1>
                <p class="mb-2">Halaman untuk menambahkan kelas baru</p>
            </header>

            <div class="data">
                <!-- Informasi Status -->
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle-fill me-2"></i> <strong>Informasi Status:</strong> Status kelas ditentukan oleh tahun ajaran yang dipilih.
                    <ul class="mb-0 mt-2">
                        <li>Kelas dengan tahun ajaran aktif akan memiliki status "Aktif"</li>
                        <li>Siswa yang ditambahkan ke kelas ini akan mengikuti status kelas</li>
                        <li>Perubahan tahun ajaran akan mempengaruhi status siswa pada kelas ini</li>
                    </ul>
                </div>
                
                <form action="{{ route('kelas.store') }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                    @csrf
                    
                    <div class="row">
                        <!-- Nama Kelas -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_kelas" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas') }}" required>
                            @error('nama_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Tingkat -->
                        <div class="col-md-6 mb-3">
                            <label for="tingkat" class="form-label">Tingkat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tingkat') is-invalid @enderror" id="tingkat" name="tingkat" value="{{ old('tingkat') }}" required>
                            @error('tingkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Contoh: 7, 8, 9, 10, 11, 12</small>
                        </div>
                        
                        <!-- Wali Kelas -->
                        <div class="col-md-6 mb-3">
                            <label for="id_guru" class="form-label">Wali Kelas</label>
                            <select class="form-select @error('id_guru') is-invalid @enderror" id="id_guru" name="id_guru">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id_guru }}" {{ old('id_guru') == $guru->id_guru ? 'selected' : '' }}>
                                        {{ $guru->nama_lengkap }} ({{ $guru->nip ?? 'Tanpa NIP' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_guru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Tahun Ajaran -->
                        <div class="col-md-6 mb-3">
                            <label for="id_tahun_ajaran" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_tahun_ajaran') is-invalid @enderror" id="id_tahun_ajaran" name="id_tahun_ajaran" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($tahunAjaranList as $tahunAjaran)
                                    <option value="{{ $tahunAjaran->id_tahun_ajaran }}" {{ old('id_tahun_ajaran') == $tahunAjaran->id_tahun_ajaran ? 'selected' : '' }}>
                                        {{ $tahunAjaran->nama_tahun_ajaran }} {{ $tahunAjaran->aktif ? '(Aktif)' : '(Non-Aktif)' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <strong>Penting:</strong> Status kelas dan siswa akan ditentukan berdasarkan tahun ajaran yang dipilih.
                                Kelas dengan tahun ajaran aktif akan memiliki status "Aktif".
                            </small>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary">Batal</a>
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
        // Initialize select2 for better dropdown experience
        $('#id_guru, #id_tahun_ajaran').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
        
        // Highlight active academic year in dropdown
        $('#id_tahun_ajaran option').each(function() {
            if ($(this).text().includes('(Aktif)')) {
                $(this).css('background-color', '#d4edda');
                $(this).css('font-weight', 'bold');
            }
        });
        
        // Show status info when academic year is selected
        $('#id_tahun_ajaran').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const isActive = selectedOption.text().includes('(Aktif)');
            
            if (selectedOption.val()) {
                let statusText = isActive ? 'Aktif' : 'Nonaktif';
                let statusClass = isActive ? 'success' : 'secondary';
                
                Swal.fire({
                    title: 'Informasi Status',
                    html: `<p>Kelas akan memiliki status <span class="badge bg-${statusClass}">${statusText}</span></p>
                          <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle-fill me-2"></i> <small>Status kelas ditentukan oleh status tahun ajaran yang dipilih.</small>
                          </div>`,
                    icon: 'info',
                    confirmButtonText: 'Mengerti'
                });
            }
        });
    });
</script>
@endsection
