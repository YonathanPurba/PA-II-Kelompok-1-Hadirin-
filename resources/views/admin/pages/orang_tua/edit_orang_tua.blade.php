@extends('layouts.admin-layout')

@section('title', 'Edit Orang Tua')

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
                        <span class="fs-5 text-muted">/ Edit Orang Tua</span>
                    </h1>
                    <p class="mb-2">Halaman untuk mengubah data orang tua</p>
                </header>

                <div class="data">
                    <form action="{{ route('orang-tua.update', $orangTua->id_orangtua) }}" method="POST" 
                        class="p-4 pt-1 rounded-4 bg-white shadow-sm" id="editOrangTuaForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id_user" value="{{ $orangTua->id_user }}">

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Informasi Orang Tua</h5>
                            </div>
                            
                            <!-- Nama Lengkap -->
                            <div class="col-md-6 mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap"
                                    class="form-control @error('nama_lengkap') is-invalid @enderror"
                                    value="{{ old('nama_lengkap', $orangTua->nama_lengkap) }}" required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div class="col-md-6 mb-3">
                                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                <input type="tel" name="nomor_telepon" id="nomor_telepon"
                                    class="form-control @error('nomor_telepon') is-invalid @enderror"
                                    value="{{ old('nomor_telepon', $orangTua->nomor_telepon) }}"
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
                                    value="{{ old('pekerjaan', $orangTua->pekerjaan) }}">
                                @error('pekerjaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status', $orangTua->status) == 'aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>
                                    <option value="nonaktif" {{ old('status', $orangTua->status) == 'nonaktif' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>
                                    <option value="pending" {{ old('status', $orangTua->status) == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                </select>
                                <small class="text-muted">Status akan otomatis diperbarui berdasarkan status anak.</small>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3"
                                    class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $orangTua->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Informasi Anak -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Data Anak</h5>
                                
                                @if($orangTua->siswa && $orangTua->siswa->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>NIS</th>
                                                    <th>Kelas</th>
                                                    <th>Status</th>
                                                    <th width="100">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orangTua->siswa as $siswa)
                                                    <tr>
                                                        <td>{{ $siswa->nama }}</td>
                                                        <td>{{ $siswa->nis ?? '-' }}</td>
                                                        <td>{{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}</td>
                                                        <td>{!! $siswa->getStatusBadgeHtml() !!}</td>
                                                        <td>
                                                            <a href="{{ route('siswa.edit', $siswa->id_siswa) }}" class="btn btn-sm btn-outline-success">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="alert alert-light border mt-3 mb-0">
                                        <i class="bi bi-lightbulb me-2"></i>
                                        <small>Untuk menambahkan atau menghapus anak, silakan edit data siswa yang bersangkutan.</small>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Belum ada siswa yang terkait dengan orang tua ini. Status orang tua saat ini adalah <strong>Pending</strong>.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orang-tua.index') }}" class="btn btn-outline-secondary">
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

@section('js')
<script>
    $(document).ready(function() {
        // Initialize select2 for better dropdown experience
        $('#status').select2({
            theme: 'bootstrap-5',
            width: '100%',
            minimumResultsForSearch: Infinity // Disable search for this dropdown
        });
        
        // Input restrictions for phone number
        $('#nomor_telepon').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 15) {
                this.value = this.value.slice(0, 15);
            }
        });
        
        // Form validation
        $('#editOrangTuaForm').on('submit', function(e) {
            let isValid = true;
            const nomor_telepon = $('#nomor_telepon').val();
            
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
