@extends('layouts.admin-layout')

@section('title', 'Edit Jadwal Pelajaran')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <!-- Header Judul -->
            <header class="judul">
                <h1 class="mb-3">
                    <a href="{{ route('jadwal-pelajaran.index') }}" class="text-decoration-none text-success">
                        Manajemen Jadwal Pelajaran
                    </a>
                    <span class="fs-5 text-muted">/ Edit Jadwal</span>
                </h1>
                <p class="mb-2">Staff dapat mengubah informasi jadwal pelajaran dengan lebih efisien</p>
            </header>

            <div class="data">
                <!-- Form Edit Jadwal -->
                <form action="{{ route('jadwal-pelajaran.update', $jadwal->id_jadwal) }}" method="POST" id="formEditJadwal">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <!-- Kelas -->
                        <div class="col-md-6 mb-3">
                            <label for="id_kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select name="id_kelas" id="id_kelas" class="form-select @error('id_kelas') is-invalid @enderror" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id_kelas }}" {{ old('id_kelas', $jadwal->id_kelas) == $kelas->id_kelas ? 'selected' : '' }}>
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
                                    <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>
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
                                    <option value="{{ $mapel->id_mata_pelajaran }}" {{ old('id_mata_pelajaran', $jadwal->id_mata_pelajaran) == $mapel->id_mata_pelajaran ? 'selected' : '' }}>
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
                                    <option value="{{ $guru->id_guru }}" {{ old('id_guru', $jadwal->id_guru) == $guru->id_guru ? 'selected' : '' }}>
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
                                        @php
                                            // Menentukan sesi yang sudah dipilih sebelumnya
                                            $selectedSessions = [];
                                            $sessionStart = 0;
                                            $sessionEnd = 0;
                                            
                                            // Cari sesi mulai berdasarkan waktu mulai
                                            foreach($sesiList as $sesi) {
                                                if(substr($jadwal->waktu_mulai, 0, 5) == substr($sesi['waktu_mulai'], 0, 5)) {
                                                    $sessionStart = $sesi['sesi'];
                                                    break;
                                                }
                                            }
                                            
                                            // Cari sesi selesai berdasarkan waktu selesai
                                            foreach($sesiList as $sesi) {
                                                if(substr($jadwal->waktu_selesai, 0, 5) == substr($sesi['waktu_selesai'], 0, 5)) {
                                                    $sessionEnd = $sesi['sesi'];
                                                    break;
                                                }
                                            }
                                            
                                            // Buat array sesi yang dipilih
                                            if($sessionStart > 0 && $sessionEnd > 0) {
                                                for($i = $sessionStart; $i <= $sessionEnd; $i++) {
                                                    $selectedSessions[] = $i;
                                                }
                                            }
                                        @endphp
                                        
                                        @foreach($sesiList as $sesi)
                                        <tr>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input sesi-checkbox" type="checkbox" 
                                                        name="sesi[]" value="{{ $sesi['sesi'] }}" 
                                                        id="sesi{{ $sesi['sesi'] }}" 
                                                        {{ in_array($sesi['sesi'], old('sesi', $selectedSessions)) ? 'checked' : '' }}>
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
                        <!-- <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="aktif" {{ old('status', $jadwal->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $jadwal->status) == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> -->
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
                        <button type="submit" class="btn btn-success" id="btnSubmit">
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
        
        // Validasi sesi berurutan
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
        
        // Trigger change untuk highlight guru pada load
        $('#id_mata_pelajaran').trigger('change');
        
        // Cek konflik jadwal saat form disubmit
        $('#formEditJadwal').on('submit', function(e) {
            e.preventDefault();
            
            // Validasi input
            const kelasId = $('#id_kelas').val();
            const guruId = $('#id_guru').val();
            const hari = $('#hari').val();
            const jadwalId = '{{ $jadwal->id_jadwal }}';
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
                return false;
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
                    sesi_selesai: sesiSelesai,
                    id_jadwal: jadwalId
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
                            html: `Terdapat konflik jadwal:<br>${conflictMessages}<br>Apakah Anda tetap ingin menyimpan perubahan?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Simpan',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#198754',
                            cancelButtonColor: '#6c757d'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Tambahkan parameter force_save
                                const form = $('#formEditJadwal');
                                form.append('<input type="hidden" name="force_save" value="1">');
                                form[0].submit();
                            }
                        });
                    } else {
                        // Tidak ada konflik, submit form
                        $('#formEditJadwal')[0].submit();
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