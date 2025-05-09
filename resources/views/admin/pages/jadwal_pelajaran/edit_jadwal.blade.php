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
                <p class="mb-2">Staff dapat mengubah informasi jadwal pelajaran</p>
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

                        <!-- Sesi Waktu Mulai -->
                        <div class="col-md-6 mb-3">
                            <label for="sesi_mulai" class="form-label">Sesi Mulai <span class="text-danger">*</span></label>
                            <select name="sesi_mulai" id="sesi_mulai" class="form-select @error('sesi_mulai') is-invalid @enderror" required>
                                <option value="">-- Pilih Sesi Mulai --</option>
                                @php
                                    $currentSesi = 0;
                                    foreach($sesiList as $index => $sesi) {
                                        if(substr($jadwal->waktu_mulai, 0, 5) == substr($sesi['waktu_mulai'], 0, 5)) {
                                            $currentSesi = $sesi['sesi'];
                                            break;
                                        }
                                    }
                                @endphp
                                @foreach($sesiList as $sesi)
                                    <option value="{{ $sesi['sesi'] }}" {{ old('sesi_mulai', $currentSesi) == $sesi['sesi'] ? 'selected' : '' }}>
                                        {{ $sesi['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sesi_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sesi Waktu Selesai -->
                        <div class="col-md-6 mb-3">
                            <label for="sesi_selesai" class="form-label">Sesi Selesai <span class="text-danger">*</span></label>
                            <select name="sesi_selesai" id="sesi_selesai" class="form-select @error('sesi_selesai') is-invalid @enderror" required>
                                <option value="">-- Pilih Sesi Selesai --</option>
                                @php
                                    $currentSesiSelesai = 0;
                                    foreach($sesiList as $index => $sesi) {
                                        if(substr($jadwal->waktu_selesai, 0, 5) == substr($sesi['waktu_selesai'], 0, 5)) {
                                            $currentSesiSelesai = $sesi['sesi'];
                                            break;
                                        }
                                    }
                                    // If not found, default to the same as start session
                                    if($currentSesiSelesai == 0) {
                                        $currentSesiSelesai = $currentSesi;
                                    }
                                @endphp
                                @foreach($sesiList as $sesi)
                                    <option value="{{ $sesi['sesi'] }}" {{ old('sesi_selesai', $currentSesiSelesai) == $sesi['sesi'] ? 'selected' : '' }}>
                                        {{ $sesi['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sesi_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="aktif" {{ old('status', $jadwal->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $jadwal->status) == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            @error('status')
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
        // Validasi sesi selesai harus >= sesi mulai
        $('#sesi_mulai, #sesi_selesai').on('change', function() {
            const sesiMulai = parseInt($('#sesi_mulai').val()) || 0;
            const sesiSelesai = parseInt($('#sesi_selesai').val()) || 0;
            
            if (sesiMulai > 0 && sesiSelesai > 0 && sesiSelesai < sesiMulai) {
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Sesi selesai harus sama dengan atau setelah sesi mulai',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754'
                });
                $('#sesi_selesai').val(sesiMulai);
            }
            
            // Disable sesi selesai yang lebih kecil dari sesi mulai
            if (sesiMulai > 0) {
                $('#sesi_selesai option').each(function() {
                    const sesiValue = parseInt($(this).val()) || 0;
                    $(this).prop('disabled', sesiValue > 0 && sesiValue < sesiMulai);
                });
            } else {
                $('#sesi_selesai option').prop('disabled', false);
            }
        });
        
        // Trigger change to apply initial validation
        $('#sesi_mulai').trigger('change');
        
        // Filter guru berdasarkan mata pelajaran yang dipilih
        $('#id_mata_pelajaran').on('change', function() {
            const mapelId = $(this).val();
            const guruSelect = $('#id_guru');
            const currentGuruId = guruSelect.val();
            
            // Reset guru select
            guruSelect.empty().append('<option value="">-- Pilih Guru --</option>');
            
            if (mapelId) {
                // Tampilkan loading spinner
                guruSelect.prop('disabled', true);
                guruSelect.after('<div id="guru-loading" class="spinner-border spinner-border-sm text-success ms-2" role="status"><span class="visually-hidden">Loading...</span></div>');
                
                // Ambil data guru berdasarkan mata pelajaran
                $.ajax({
                    url: `/api/mata-pelajaran/${mapelId}/guru-pengampu`,
                    method: 'GET',
                    success: function(response) {
                        // Hapus loading spinner
                        $('#guru-loading').remove();
                        guruSelect.prop('disabled', false);
                        
                        if (response.success && response.data.length > 0) {
                            // Tambahkan opsi guru
                            response.data.forEach(function(guru) {
                                const selected = guru.id_guru == currentGuruId ? 'selected' : '';
                                guruSelect.append(`<option value="${guru.id_guru}" ${selected}>${guru.nama_lengkap}</option>`);
                            });
                        } else {
                            // Jika tidak ada guru, tampilkan pesan
                            Swal.fire({
                                title: 'Perhatian!',
                                text: 'Tidak ada guru yang mengajar mata pelajaran ini.',
                                icon: 'warning',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#198754'
                            });
                        }
                    },
                    error: function() {
                        // Hapus loading spinner
                        $('#guru-loading').remove();
                        guruSelect.prop('disabled', false);
                        
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal memuat data guru.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#198754'
                        });
                    }
                });
            } else {
                // Disable guru select jika tidak ada mata pelajaran yang dipilih
                guruSelect.prop('disabled', true);
            }
        });
        
        // Cek konflik jadwal saat form disubmit
        $('#formEditJadwal').on('submit', function(e) {
            e.preventDefault();
            
            const kelasId = $('#id_kelas').val();
            const guruId = $('#id_guru').val();
            const hari = $('#hari').val();
            const sesiMulai = $('#sesi_mulai').val();
            const sesiSelesai = $('#sesi_selesai').val();
            const jadwalId = '{{ $jadwal->id_jadwal }}';
            
            // Validasi input
            if (!kelasId || !guruId || !hari || !sesiMulai || !sesiSelesai) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Silakan lengkapi semua field yang diperlukan.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754'
                });
                return false;
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
                                $('#formEditJadwal')[0].submit();
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
