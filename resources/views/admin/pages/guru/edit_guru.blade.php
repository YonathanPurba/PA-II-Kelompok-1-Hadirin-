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
                        class="p-4 pt-1 rounded-4 bg-white shadow-sm" id="formEditGuru">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Informasi Guru</h5>
                            </div>
                            
                            <!-- Nama Lengkap -->
                            <div class="col-md-6 mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap"
                                    class="form-control @error('nama_lengkap') is-invalid @enderror"
                                    value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NIP -->
                            <div class="col-md-6 mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" name="nip" id="nip"
                                    class="form-control @error('nip') is-invalid @enderror"
                                    value="{{ old('nip', $guru->nip) }}"
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
                                <input type="text" name="nomor_telepon" id="nomor_telepon"
                                    class="form-control @error('nomor_telepon') is-invalid @enderror"
                                    value="{{ old('nomor_telepon', $guru->nomor_telepon) }}"
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
                                    value="{{ old('bidang_studi', $guru->bidang_studi) }}">
                                @error('bidang_studi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status', $guru->status) == 'aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>
                                    <option value="nonaktif" {{ old('status', $guru->status) == 'nonaktif' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>
                                </select>
                                @error('status')
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
                                                        {{ in_array($mapel->id_mata_pelajaran, old('mata_pelajaran', $guru->mataPelajaran->pluck('id_mata_pelajaran')->toArray())) ? 'checked' : '' }}>
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
                                <div class="form-text mt-2">Pilih minimal satu mata pelajaran</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Data Jadwal Mengajar</h5>
                                
                                @if($guru->jadwal && $guru->jadwal->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Hari</th>
                                                    <th>Waktu</th>
                                                    <th>Kelas</th>
                                                    <th>Mata Pelajaran</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($guru->jadwal as $jadwal)
                                                    <tr>
                                                        <td>{{ ucfirst($jadwal->hari) }}</td>
                                                        <td>{{ date('H:i', strtotime($jadwal->waktu_mulai)) }} - {{ date('H:i', strtotime($jadwal->waktu_selesai)) }}</td>
                                                        <td>{{ $jadwal->kelas->nama_kelas ?? 'Belum ada kelas' }}</td>
                                                        <td>{{ $jadwal->mataPelajaran->nama ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="alert alert-light border mt-3 mb-0">
                                        <i class="bi bi-lightbulb me-2"></i>
                                        <small>Untuk menambah atau mengubah jadwal, silakan kelola di menu Jadwal Pelajaran.</small>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Belum ada jadwal mengajar untuk guru ini.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/guru') }}" class="btn btn-outline-secondary">
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
        
        // Form validation
        $('#formEditGuru').on('submit', function(e) {
            let isValid = true;
            const nip = $('#nip').val();
            const mataPelajaran = $('input[name="mata_pelajaran[]"]:checked').length;
            
            // NIP validation
            if (nip && nip.length !== 18) {
                e.preventDefault();
                alert('NIP harus terdiri dari 18 digit!');
                isValid = false;
            }
            
            // Mata pelajaran validation
            if (mataPelajaran === 0) {
                e.preventDefault();
                alert('Pilih minimal satu mata pelajaran!');
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
