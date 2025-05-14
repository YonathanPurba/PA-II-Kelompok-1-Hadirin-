@extends('layouts.admin-layout')

@section('title', 'Tambah Jadwal Pelajaran')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <header class="judul">
                <h1 class="mb-3">
                    <a href="{{ route('jadwal-pelajaran.index') }}" class="text-decoration-none text-success">
                        Manajemen Jadwal Pelajaran
                    </a>
                    <span class="fs-5 text-muted">/ Tambah Jadwal</span>
                </h1>
                <p class="mb-2">Staff dapat menambahkan jadwal pelajaran baru dengan lebih efisien</p>
            </header>

            <div class="data">
                <!-- Tabs untuk metode pembuatan jadwal -->
                <ul class="nav nav-tabs mb-4" id="jadwalTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="kelas-tab" data-bs-toggle="tab" data-bs-target="#kelas-content" 
                                type="button" role="tab" aria-controls="kelas-content" aria-selected="true">
                            Per Kelas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="massal-tab" data-bs-toggle="tab" data-bs-target="#massal-content" 
                                type="button" role="tab" aria-controls="massal-content" aria-selected="false">
                            Pembuatan Massal
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="jadwalTabContent">
                    <!-- Tab Per Kelas -->
                    <div class="tab-pane fade show active" id="kelas-content" role="tabpanel" aria-labelledby="kelas-tab">
                        <form action="{{ route('jadwal-pelajaran.store') }}" method="POST" id="formTambahJadwalKelas">
                            @csrf
                            <input type="hidden" name="mode" value="kelas">

                            <div class="row mb-4">
                                <!-- Kelas -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select name="id_kelas" id="id_kelas" class="form-select @error('id_kelas') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($kelasList as $kelas)
                                            <option value="{{ $kelas->id_kelas }}" {{ old('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                                {{ $kelas->tingkat }} {{ $kelas->nama_kelas }} - {{ $kelas->tahunAjaran->nama_tahun_ajaran ?? 'Tidak ada tahun ajaran' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Hari -->
                                <div class="col-md-6 mb-3">
                                    <label for="hari" class="form-label">Hari <span class="text-danger">*</span></label>
                                    <select name="hari" id="hari" class="form-select @error('hari') is-invalid @enderror" required>
                                        <option value="">-- Pilih Hari --</option>
                                        @foreach($hariList as $hari)
                                            <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>
                                                {{ ucfirst($hari) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hari')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mata Pelajaran -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_mata_pelajaran" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select name="id_mata_pelajaran" id="id_mata_pelajaran" class="form-select @error('id_mata_pelajaran') is-invalid @enderror" required>
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach($mataPelajaranList as $mapel)
                                            <option value="{{ $mapel->id_mata_pelajaran }}" {{ old('id_mata_pelajaran') == $mapel->id_mata_pelajaran ? 'selected' : '' }}>
                                                {{ $mapel->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_mata_pelajaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Guru -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_guru" class="form-label">Guru <span class="text-danger">*</span></label>
                                    <select name="id_guru" id="id_guru" class="form-select @error('id_guru') is-invalid @enderror" required>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach($guruList as $guru)
                                            <option value="{{ $guru->id_guru }}" {{ old('id_guru') == $guru->id_guru ? 'selected' : '' }}>
                                                {{ $guru->nama_lengkap }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_guru')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sesi Waktu -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Sesi Waktu <span class="text-danger">*</span></label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-center">Pilih</th>
                                                    <th>Sesi</th>
                                                    <th>Waktu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($sesiList as $sesi)
                                                <tr>
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input sesi-checkbox" type="checkbox" 
                                                                name="sesi[]" value="{{ $sesi['sesi'] }}" 
                                                                id="sesi{{ $sesi['sesi'] }}" 
                                                                {{ in_array($sesi['sesi'], old('sesi', [])) ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>Sesi {{ $sesi['sesi'] }}</td>
                                                    <td>{{ substr($sesi['waktu_mulai'], 0, 5) }} - {{ substr($sesi['waktu_selesai'], 0, 5) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @error('sesi')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tahun Ajaran -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_tahun_ajaran" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                                    <select name="id_tahun_ajaran" id="id_tahun_ajaran" class="form-select @error('id_tahun_ajaran') is-invalid @enderror" required>
                                        <option value="">Pilih Tahun Ajaran</option>
                                        @foreach($tahunAjaranList as $ta)
                                            <option value="{{ $ta->id_tahun_ajaran }}" 
                                                {{ old('id_tahun_ajaran', $tahunAjaranAktif->id_tahun_ajaran ?? '') == $ta->id_tahun_ajaran ? 'selected' : '' }}
                                                {{ $ta->aktif ? 'class=fw-bold text-success' : '' }}>
                                                {{ $ta->nama_tahun_ajaran }} {{ $ta->aktif ? '(Aktif)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_tahun_ajaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Informasi Jadwal -->
                            <div class="alert alert-info mb-4">
                                <i class="bi bi-info-circle me-2"></i> <strong>Informasi Jadwal:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Jadwal dimulai pukul 07:45 pagi</li>
                                    <li>Setiap sesi pelajaran berdurasi 45 menit</li>
                                    <li>Istirahat 15 menit setelah sesi ketiga (10:00 - 10:15)</li>
                                    <li>Anda dapat memilih beberapa sesi berurutan untuk satu mata pelajaran</li>
                                    <li>Sistem akan otomatis memeriksa konflik jadwal</li>
                                </ul>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="text-end mt-4">
                                <a href="{{ route('jadwal-pelajaran.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success" id="btnSubmitKelas">
                                    <i class="bi bi-save me-1"></i> Simpan Jadwal
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Pembuatan Massal -->
                    <div class="tab-pane fade" id="massal-content" role="tabpanel" aria-labelledby="massal-tab">
                        <div class="alert alert-primary mb-4">
                            <i class="bi bi-info-circle me-2"></i> <strong>Pembuatan Jadwal Massal</strong>
                            <p class="mb-0 mt-2">Fitur ini memungkinkan Anda membuat jadwal untuk satu kelas sekaligus dengan menggunakan tabel jadwal mingguan. Pilih mata pelajaran dan guru untuk setiap sesi dan hari yang diinginkan.</p>
                        </div>
                        
                        <form action="{{ route('jadwal-pelajaran.store-massal') }}" method="POST" id="formTambahJadwalMassal">
                            @csrf
                            <input type="hidden" name="mode" value="massal">
                            
                            <div class="row mb-4">
                                <!-- Pilih Kelas -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_kelas_massal" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select name="id_kelas" id="id_kelas_massal" class="form-select @error('id_kelas') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($kelasList as $kelas)
                                            <option value="{{ $kelas->id_kelas }}" {{ old('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                                {{ $kelas->tingkat }} {{ $kelas->nama_kelas }} - {{ $kelas->tahunAjaran->nama_tahun_ajaran ?? 'Tidak ada tahun ajaran' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Tombol untuk memuat tabel jadwal -->
                                <div class="col-md-6 mb-3 d-flex align-items-end">
                                    <button type="button" id="btnLoadJadwalTable" class="btn btn-primary">
                                        <i class="bi bi-table me-1"></i> Muat Tabel Jadwal
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Tabel Jadwal Mingguan -->
                            <div id="jadwal_table_container" class="mb-4" style="display: none;">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">Jadwal Mingguan <span id="kelas_name"></span></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Sesi</th>
                                                        <th>Waktu</th>
                                                        @foreach($hariList as $hari)
                                                            <th>{{ ucfirst($hari) }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sesiList as $sesi)
                                                        <tr>
                                                            <td>Sesi {{ $sesi['sesi'] }}</td>
                                                            <td>{{ substr($sesi['waktu_mulai'], 0, 5) }} - {{ substr($sesi['waktu_selesai'], 0, 5) }}</td>
                                                            @foreach($hariList as $hari)
                                                                <td>
                                                                    <div class="jadwal-cell" data-sesi="{{ $sesi['sesi'] }}" data-hari="{{ $hari }}">
                                                                        <select name="jadwal[{{ $hari }}][{{ $sesi['sesi'] }}][id_mata_pelajaran]" class="form-select form-select-sm mb-1 mapel-select">
                                                                            <option value="">-- Mapel --</option>
                                                                            @foreach($mataPelajaranList as $mapel)
                                                                                <option value="{{ $mapel->id_mata_pelajaran }}">{{ $mapel->nama }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <select name="jadwal[{{ $hari }}][{{ $sesi['sesi'] }}][id_guru]" class="form-select form-select-sm guru-select">
                                                                            <option value="">-- Guru --</option>
                                                                            @foreach($guruList as $guru)
                                                                                <option value="{{ $guru->id_guru }}">{{ $guru->nama_lengkap }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="alert alert-info mt-3">
                                            <i class="bi bi-info-circle me-2"></i> <strong>Petunjuk:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Pilih mata pelajaran dan guru untuk setiap sesi dan hari yang diinginkan</li>
                                                <li>Sel kosong tidak akan dibuat jadwalnya</li>
                                                <li>Sistem akan memeriksa konflik jadwal sebelum menyimpan</li>
                                                <li>Jadwal yang sudah ada untuk kelas ini akan tetap dipertahankan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tombol Submit -->
                                <div class="text-end mt-4">
                                    <a href="{{ route('jadwal-pelajaran.index') }}" class="btn btn-outline-secondary me-2">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success" id="btnSubmitMassal">
                                        <i class="bi bi-save me-1"></i> Simpan Semua Jadwal
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Fungsi untuk memastikan checkbox sesi berurutan
        function validateConsecutiveSessions() {
            $(document).on('change', '.sesi-checkbox', function() {
                let selectedSessions = [];
                $('.sesi-checkbox:checked').each(function() {
                    selectedSessions.push(parseInt($(this).val()));
                });
                
                // Jika tidak ada yang dipilih, tidak perlu validasi
                if (selectedSessions.length === 0) return;
                
                // Urutkan sesi
                selectedSessions.sort((a, b) => a - b);
                
                // Cek apakah berurutan
                let isConsecutive = true;
                for (let i = 1; i < selectedSessions.length; i++) {
                    // Khusus untuk sesi 3 ke 4 ada istirahat, jadi boleh loncat
                    if (selectedSessions[i-1] === 3 && selectedSessions[i] === 4) {
                        continue;
                    }
                    
                    if (selectedSessions[i] !== selectedSessions[i-1] + 1) {
                        isConsecutive = false;
                        break;
                    }
                }
                
                if (!isConsecutive) {
                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Sesi yang dipilih harus berurutan.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#198754'
                    });
                    
                    // Uncheck the last clicked checkbox
                    $(this).prop('checked', false);
                }
            });
        }
        
        // Validasi sesi berurutan untuk tab kelas
        validateConsecutiveSessions();

        // Highlight guru yang mengajar mata pelajaran tertentu
        $('#id_mata_pelajaran').on('change', function() {
            const mapelId = $(this).val();
            
            // Reset semua opsi guru
            $('#id_guru option').removeClass('text-success fw-bold');
            
            if (mapelId) {
                // Highlight guru yang mengajar mata pelajaran ini
                @foreach($guruList as $guru)
                    @foreach($guru->mataPelajaran as $mapel)
                        if ('{{ $mapel->id_mata_pelajaran }}' === mapelId) {
                            $('#id_guru option[value="{{ $guru->id_guru }}"]').addClass('text-success fw-bold');
                        }
                    @endforeach
                @endforeach
            }
        });
        
        // Cek konflik jadwal sebelum submit (tab kelas)
        $('#formTambahJadwalKelas').on('submit', function(e) {
            e.preventDefault();
            
            // Validasi input
            const kelasId = $('#id_kelas').val();
            const guruId = $('#id_guru').val();
            const hari = $('#hari').val();
            const selectedSessions = [];
            
            $('.sesi-checkbox:checked').each(function() {
                selectedSessions.push(parseInt($(this).val()));
            });
            
            if (!kelasId || !guruId || !hari || selectedSessions.length === 0) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Silakan lengkapi semua field yang diperlukan.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754'
                });
                return;
            }
            
            // Urutkan sesi
            selectedSessions.sort((a, b) => a - b);
            
            // Tentukan sesi mulai dan selesai
            const sesiMulai = selectedSessions[0];
            const sesiSelesai = selectedSessions[selectedSessions.length - 1];
            
            // Tampilkan loading
            Swal.fire({
                title: 'Memeriksa Konflik Jadwal',
                text: 'Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Cek konflik jadwal
            $.ajax({
                url: '/jadwal-pelajaran/check-conflicts',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id_kelas: kelasId,
                    id_guru: guruId,
                    hari: hari,
                    sesi_mulai: sesiMulai,
                    sesi_selesai: sesiSelesai
                },
                success: function(response) {
                    Swal.close();
                    
                    if (!response.success) {
                        // Tampilkan peringatan konflik
                        let conflictMessages = '';
                        response.conflicts.forEach(function(conflict) {
                            conflictMessages += `- ${conflict.message}<br>`;
                        });
                        
                        Swal.fire({
                            title: 'Konflik Jadwal Terdeteksi!',
                            html: `Terdapat konflik jadwal:<br>${conflictMessages}<br>Apakah Anda tetap ingin menyimpan jadwal?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Simpan',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#198754',
                            cancelButtonColor: '#6c757d'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Tambahkan parameter force_save
                                const form = $('#formTambahJadwalKelas');
                                form.append('<input type="hidden" name="force_save" value="1">');
                                form[0].submit();
                            }
                        });
                    } else {
                        // Tidak ada konflik, submit form
                        $('#formTambahJadwalKelas')[0].submit();
                    }
                },
                error: function() {
                    Swal.close();
                    
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal memeriksa konflik jadwal.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#198754'
                    });
                }
            });
        });
        
        // Muat tabel jadwal untuk pembuatan massal
        $('#btnLoadJadwalTable').on('click', function() {
            const kelasId = $('#id_kelas_massal').val();
            
            if (!kelasId) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Silakan pilih kelas terlebih dahulu.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754'
                });
                return;
            }
            
            // Tampilkan nama kelas yang dipilih
            const kelasName = $('#id_kelas_massal option:selected').text();
            $('#kelas_name').text(kelasName);
            
            // Tampilkan tabel jadwal
            $('#jadwal_table_container').show();
            
            // Scroll ke tabel jadwal
            $('html, body').animate({
                scrollTop: $('#jadwal_table_container').offset().top - 100
            }, 500);
            
            // Highlight guru yang mengajar mata pelajaran tertentu
            $('.mapel-select').on('change', function() {
                const mapelId = $(this).val();
                const guruSelect = $(this).closest('.jadwal-cell').find('.guru-select');
                
                // Reset semua opsi guru
                guruSelect.find('option').removeClass('text-success fw-bold');
                
                if (mapelId) {
                    // Highlight guru yang mengajar mata pelajaran ini
                    @foreach($guruList as $guru)
                        @foreach($guru->mataPelajaran as $mapel)
                            if ('{{ $mapel->id_mata_pelajaran }}' === mapelId) {
                                guruSelect.find('option[value="{{ $guru->id_guru }}"]').addClass('text-success fw-bold');
                            }
                        @endforeach
                    @endforeach
                }
            });
            
            // Cek apakah sudah ada jadwal untuk kelas ini
            $.ajax({
                url: '/jadwal-pelajaran/kelas/' + kelasId,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data.length > 0) {
                        // Isi tabel dengan jadwal yang sudah ada
                        response.data.forEach(function(jadwal) {
                            // Tentukan sesi berdasarkan waktu
                            const waktuMulai = jadwal.waktu_mulai.substring(0, 5);
                            let sesi = 0;
                            
                            @foreach($sesiList as $sesi)
                                if ('{{ substr($sesi["waktu_mulai"], 0, 5) }}' === waktuMulai) {
                                    sesi = {{ $sesi['sesi'] }};
                                }
                            @endforeach
                            
                            if (sesi > 0) {
                                const cell = $(`.jadwal-cell[data-hari="${jadwal.hari}"][data-sesi="${sesi}"]`);
                                cell.find('.mapel-select').val(jadwal.id_mata_pelajaran);
                                cell.find('.guru-select').val(jadwal.id_guru);
                                
                                // Trigger change untuk highlight guru
                                cell.find('.mapel-select').trigger('change');
                            }
                        });
                    }
                }
            });
        });
        
        // Cek konflik jadwal sebelum submit (tab massal)
        $('#formTambahJadwalMassal').on('submit', function(e) {
            e.preventDefault();
            
            // Validasi input
            const kelasId = $('#id_kelas_massal').val();
            
            if (!kelasId) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Silakan pilih kelas terlebih dahulu.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754'
                });
                return;
            }
            
            // Cek apakah ada jadwal yang diisi
            let hasSchedule = false;
            $('.mapel-select').each(function() {
                if ($(this).val()) {
                    hasSchedule = true;
                    return false; // break the loop
                }
            });
            
            if (!hasSchedule) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Silakan isi minimal satu jadwal.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754'
                });
                return;
            }
            
            // Validasi guru untuk setiap mata pelajaran yang dipilih
            let isValid = true;
            $('.jadwal-cell').each(function() {
                const mapelSelect = $(this).find('.mapel-select');
                const guruSelect = $(this).find('.guru-select');
                
                if (mapelSelect.val() && !guruSelect.val()) {
                    isValid = false;
                    mapelSelect.addClass('is-invalid');
                    guruSelect.addClass('is-invalid');
                } else {
                    mapelSelect.removeClass('is-invalid');
                    guruSelect.removeClass('is-invalid');
                }
            });
            
            if (!isValid) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Silakan pilih guru untuk setiap mata pelajaran yang dipilih.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754'
                });
                return;
            }
            
            // Tampilkan loading
            Swal.fire({
                title: 'Memeriksa Konflik Jadwal',
                text: 'Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Kumpulkan data jadwal
            const jadwalData = [];
            $('.jadwal-cell').each(function() {
                const sesi = $(this).data('sesi');
                const hari = $(this).data('hari');
                const mapelId = $(this).find('.mapel-select').val();
                const guruId = $(this).find('.guru-select').val();
                
                if (mapelId && guruId) {
                    jadwalData.push({
                        id_kelas: kelasId,
                        hari: hari,
                        sesi: sesi,
                        id_mata_pelajaran: mapelId,
                        id_guru: guruId
                    });
                }
            });
            
            // Cek konflik jadwal
            $.ajax({
                url: '/jadwal-pelajaran/check-conflicts-massal',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    jadwal_data: jadwalData
                },
                success: function(response) {
                    Swal.close();
                    
                    if (!response.success) {
                        // Tampilkan peringatan konflik
                        let conflictMessages = '';
                        response.conflicts.forEach(function(conflict) {
                            conflictMessages += `- ${conflict.message}<br>`;
                        });
                        
                        Swal.fire({
                            title: 'Konflik Jadwal Terdeteksi!',
                            html: `Terdapat konflik jadwal:<br>${conflictMessages}<br>Apakah Anda tetap ingin menyimpan jadwal?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Simpan',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#198754',
                            cancelButtonColor: '#6c757d'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Tambahkan parameter force_save
                                const form = $('#formTambahJadwalMassal');
                                form.append('<input type="hidden" name="force_save" value="1">');
                                form[0].submit();
                            }
                        });
                    } else {
                        // Tidak ada konflik, submit form
                        $('#formTambahJadwalMassal')[0].submit();
                    }
                },
                error: function() {
                    Swal.close();
                    
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal memeriksa konflik jadwal.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#198754'
                    });
                }
            });
        });
    });
</script>
@endsection
