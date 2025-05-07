@extends('layouts.admin-layout')

@section('title', 'Edit Kelas')

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
                    <span class="fs-5 text-muted">/ Edit Kelas</span>
                </h1>
                <p class="mb-2">Halaman untuk mengubah informasi kelas</p>
            </header>

            <div class="data">
                <!-- Informasi Status -->
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Perhatian:</strong> Mengubah tahun ajaran kelas akan mempengaruhi status siswa di kelas ini.
                    <ul class="mb-0 mt-2">
                        <li>Jika kelas dipindahkan ke tahun ajaran aktif, semua siswa di kelas ini akan menjadi "Aktif"</li>
                        <li>Jika kelas dipindahkan ke tahun ajaran nonaktif, semua siswa di kelas ini akan menjadi "Nonaktif"</li>
                        <li>Status siswa akan otomatis diperbarui saat menyimpan perubahan</li>
                    </ul>
                </div>
                
                <form action="{{ route('kelas.update', $kelas->id_kelas) }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Nama Kelas -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_kelas" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                            @error('nama_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Tingkat -->
                        <div class="col-md-6 mb-3">
                            <label for="tingkat" class="form-label">Tingkat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tingkat') is-invalid @enderror" id="tingkat" name="tingkat" value="{{ old('tingkat', $kelas->tingkat) }}" required>
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
                                    <option value="{{ $guru->id_guru }}" {{ old('id_guru', $kelas->id_guru) == $guru->id_guru ? 'selected' : '' }}>
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
                                    <option value="{{ $tahunAjaran->id_tahun_ajaran }}" {{ old('id_tahun_ajaran', $kelas->id_tahun_ajaran) == $tahunAjaran->id_tahun_ajaran ? 'selected' : '' }}>
                                        {{ $tahunAjaran->nama_tahun_ajaran }} {{ $tahunAjaran->aktif ? '(Aktif)' : '(Non-Aktif)' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <strong class="text-danger">Perhatian:</strong> Mengubah tahun ajaran akan mempengaruhi status siswa di kelas ini.
                                Status kelas dan siswa akan ditentukan berdasarkan tahun ajaran yang dipilih.
                            </small>
                        </div>
                    </div>
                    
                    <!-- Informasi Status Saat Ini -->
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i> <strong>Status Saat Ini:</strong>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <p class="mb-1">Status Kelas: {!! $kelas->getStatusBadgeHtml() !!}</p>
                                <p class="mb-1">Tahun Ajaran: {{ $kelas->tahunAjaran ? $kelas->tahunAjaran->nama_tahun_ajaran : 'Tidak ada' }}
                                    @if($kelas->tahunAjaran && $kelas->tahunAjaran->aktif)
                                        <span class="badge bg-success">Aktif</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1">Jumlah Siswa: <span class="badge bg-info">{{ $kelas->siswa->count() }} siswa</span></p>
                                <p class="mb-1">
                                    <small class="text-muted">Status siswa akan diperbarui jika tahun ajaran berubah</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary">Batal</a>
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
        
        // Confirm before changing academic year if there are students
        const studentCount = {{ $kelas->siswa->count() }};
        const originalTahunAjaran = '{{ $kelas->id_tahun_ajaran }}';
        
        $('form').on('submit', function(e) {
            const newTahunAjaran = $('#id_tahun_ajaran').val();
            
            if (studentCount > 0 && originalTahunAjaran !== newTahunAjaran) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Konfirmasi Perubahan Tahun Ajaran',
                    html: `<p>Anda akan mengubah tahun ajaran kelas ini yang memiliki <strong>${studentCount} siswa</strong>.</p>
                          <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Perhatian!</strong> Tindakan ini akan:
                            <ul class="mb-0 mt-1 text-start">
                              <li>Mengubah status semua siswa di kelas ini</li>
                              <li>Memperbarui tahun ajaran siswa sesuai dengan kelas</li>
                            </ul>
                          </div>
                          <p>Apakah Anda yakin ingin melanjutkan?</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).off('submit').submit();
                    }
                });
            }
        });
    });
</script>
@endsection
