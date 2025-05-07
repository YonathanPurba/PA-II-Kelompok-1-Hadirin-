@extends('layouts.admin-layout')

@section('title', 'Edit Data Siswa')

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
                        <span class="fs-5 text-muted">/ Edit Data Siswa</span>
                    </h1>
                    <p class="mb-2">Halaman untuk mengubah informasi siswa</p>
                </header>

                <div class="data">
                    <!-- Informasi Status -->
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Perhatian:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Mengubah kelas siswa akan otomatis mengubah status siswa sesuai dengan status tahun ajaran kelas</li>
                            <li>Status siswa dapat diubah secara manual, tetapi akan otomatis diperbarui jika status kelas atau tahun ajaran berubah</li>
                            <li>Tahun ajaran siswa akan otomatis diperbarui sesuai dengan tahun ajaran kelas</li>
                        </ul>
                    </div>

                    <form action="{{ route('siswa.update', $siswa->id_siswa) }}" method="POST"
                        class="p-4 pt-1 rounded-4 bg-white shadow-sm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Nama Lengkap -->
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $siswa->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NIS -->
                            <div class="col-md-6 mb-3">
                                <label for="nis" class="form-label">NIS</label>
                                <input type="text" name="nis" id="nis"
                                    class="form-control @error('nis') is-invalid @enderror"
                                    value="{{ old('nis', $siswa->nis) }}" required>
                                @error('nis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="col-md-6 mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin"
                                    class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('jenis_kelamin', $siswa->jenis_kelamin) == '' ? 'selected' : '' }}>
                                        -- Pilih --
                                    </option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki
                                    </option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan
                                    </option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kelas -->
                            <div class="col-md-6 mb-3">
                                <label for="id_kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select name="id_kelas" id="id_kelas"
                                    class="form-select @error('id_kelas') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id_kelas }}"
                                            {{ old('id_kelas', $siswa->id_kelas) == $kelas->id_kelas ? 'selected' : '' }}
                                            data-tahun-ajaran="{{ $kelas->tahunAjaran ? $kelas->tahunAjaran->nama_tahun_ajaran : '-' }}"
                                            data-status="{{ $kelas->isActive() ? 'aktif' : 'nonaktif' }}">
                                            {{ $kelas->nama_kelas }}
                                            @if($kelas->tahunAjaran)
                                                ({{ $kelas->tahunAjaran->nama_tahun_ajaran }})
                                                @if($kelas->tahunAjaran->aktif)
                                                    - Aktif
                                                @else
                                                    - Non-Aktif
                                                @endif
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    <span class="text-info">Info:</span> Kelas saat ini: 
                                    @if($siswa->kelas)
                                        {{ $siswa->kelas->nama_kelas }}
                                        @if($siswa->kelas->tahunAjaran)
                                            ({{ $siswa->kelas->tahunAjaran->nama_tahun_ajaran }})
                                            @if($siswa->kelas->tahunAjaran->aktif)
                                                - <span class="text-success">Aktif</span>
                                            @else
                                                - <span class="text-secondary">Non-Aktif</span>
                                            @endif
                                        @endif
                                    @else
                                        Tidak ada
                                    @endif
                                </div>
                                @error('id_kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                    class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                    value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '') }}">
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
                                            {{ old('id_orangtua', $siswa->id_orangtua) == $orangTua->id_orangtua ? 'selected' : '' }}>
                                            {{ $orangTua->nama_lengkap }}
                                            @if($orangTua->status == 'aktif')
                                                (Aktif)
                                            @elseif($orangTua->status == 'nonaktif')
                                                (Non-Aktif)
                                            @elseif($orangTua->status == 'pending')
                                                (Pending)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    @if($siswa->id_orangtua)
                                        Siswa ini saat ini terkait dengan orang tua: {{ $siswa->orangTua->nama_lengkap ?? 'Tidak diketahui' }}
                                    @else
                                        <span class="text-danger">Siswa ini belum memiliki orang tua terkait. Silakan pilih orang tua.</span>
                                    @endif
                                </div>
                                @error('id_orangtua')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status', $siswa->status) == 'aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>
                                    <option value="nonaktif" {{ old('status', $siswa->status) == 'nonaktif' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>
                                </select>
                                <div class="form-text text-warning">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Status ini dapat diubah secara manual, tetapi akan otomatis diperbarui jika status kelas atau tahun ajaran berubah.
                                </div>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3"
                                    class="form-control @error('alamat') is-invalid @enderror"
                                    >{{ old('alamat', $siswa->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informasi Tahun Ajaran -->
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i> <strong>Informasi Tahun Ajaran:</strong>
                            <p class="mb-0">
                                Tahun ajaran siswa saat ini: 
                                @if($siswa->tahunAjaran)
                                    <strong>{{ $siswa->tahunAjaran->nama_tahun_ajaran }}</strong>
                                    @if($siswa->tahunAjaran->aktif)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Non-Aktif</span>
                                    @endif
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </p>
                            <p class="mb-0 mt-2">
                                Tahun ajaran akan otomatis diperbarui sesuai dengan tahun ajaran kelas yang dipilih.
                            </p>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
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
        $('#id_kelas, #id_orangtua').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
        
        // Update status based on selected class
        $('#id_kelas').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const kelasStatus = selectedOption.data('status');
            
            if (kelasStatus) {
                $('#status').val(kelasStatus);
                
                // Show alert about status change
                if (kelasStatus === 'aktif') {
                    Swal.fire({
                        title: 'Status Akan Berubah',
                        html: 'Status siswa akan diubah menjadi <strong>Aktif</strong> karena kelas yang dipilih berada pada tahun ajaran aktif.',
                        icon: 'info',
                        confirmButtonText: 'Mengerti'
                    });
                } else {
                    Swal.fire({
                        title: 'Status Akan Berubah',
                        html: 'Status siswa akan diubah menjadi <strong>Nonaktif</strong> karena kelas yang dipilih berada pada tahun ajaran nonaktif.',
                        icon: 'info',
                        confirmButtonText: 'Mengerti'
                    });
                }
            }
        });
        
        // Highlight active classes in dropdown
        $('#id_kelas option').each(function() {
            if ($(this).text().includes('- Aktif')) {
                $(this).css('background-color', '#d4edda');
                $(this).css('font-weight', 'bold');
            } else if ($(this).text().includes('- Non-Aktif')) {
                $(this).css('background-color', '#f8f9fa');
            }
        });
    });
</script>
@endsection
