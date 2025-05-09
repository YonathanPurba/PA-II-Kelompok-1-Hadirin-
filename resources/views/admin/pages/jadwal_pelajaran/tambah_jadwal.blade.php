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
                <p class="mb-2">Staff dapat menambahkan jadwal pelajaran baru</p>
            </header>

            <div class="data">
                <form action="{{ route('jadwal-pelajaran.store') }}" method="POST" id="formTambahJadwal">
                    @csrf

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
                            <select name="id_guru" id="id_guru" class="form-select @error('id_guru') is-invalid @enderror" required disabled>
                                <option value="">-- Pilih Guru --</option>
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
                                @foreach($sesiList as $sesi)
                                    <option value="{{ $sesi['sesi'] }}" {{ old('sesi_mulai') == $sesi['sesi'] ? 'selected' : '' }}>
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
                                @foreach($sesiList as $sesi)
                                    <option value="{{ $sesi['sesi'] }}" {{ old('sesi_selesai') == $sesi['sesi'] ? 'selected' : '' }}>
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
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
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
                            <i class="bi bi-save me-1"></i> Simpan Jadwal
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

        // Load guru saat mata pelajaran dipilih
        $('#id_mata_pelajaran').on('change', function() {
            const mapelId = $(this).val();
            const guruSelect = $('#id_guru');
            
            guruSelect.empty().append('<option value="">-- Pilih Guru --</option>').prop('disabled', true);
            if (!mapelId) return;

            guruSelect.after('<div id="guru-loading" class="spinner-border spinner-border-sm text-success ms-2" role="status"><span class="visually-hidden">Loading...</span></div>');

            $.ajax({
                url: `/api/mata-pelajaran/${mapelId}/guru-pengampu`,
                method: 'GET',
                success: function(res) {
                    $('#guru-loading').remove();
                    guruSelect.prop('disabled', false);
                    if (res.success && res.data.length > 0) {
                        res.data.forEach(guru => {
                            guruSelect.append(`<option value="${guru.id_guru}">${guru.nama_lengkap}</option>`);
                        });
                    } else {
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
        });

        // Cek konflik sebelum submit
        $('#formTambahJadwal').on('submit', function(e) {
            e.preventDefault();

            const data = {
                id_kelas: $('#id_kelas').val(),
                id_guru: $('#id_guru').val(),
                hari: $('#hari').val(),
                sesi_mulai: $('#sesi_mulai').val(),
                sesi_selesai: $('#sesi_selesai').val()
            };

            if (!data.id_kelas || !data.id_guru || !data.hari || !data.sesi_mulai || !data.sesi_selesai) {
                Swal.fire('Error!', 'Silakan lengkapi semua field yang diperlukan.', 'error');
                return;
            }

            Swal.fire({
                title: 'Memeriksa Konflik Jadwal',
                text: 'Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.post({
                url: '/jadwal-pelajaran/check-conflicts',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data,
                success: function(res) {
                    Swal.close();
                    if (!res.success) {
                        let messages = res.conflicts.map(c => `- ${c.message}`).join('<br>');
                        Swal.fire({
                            title: 'Konflik Jadwal Ditemukan!',
                            html: `Terdapat konflik:<br>${messages}<br>Apakah Anda ingin lanjut?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Simpan',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#198754',
                            cancelButtonColor: '#6c757d'
                        }).then(result => {
                            if (result.isConfirmed) {
                                $('#formTambahJadwal')[0].submit();
                            }
                        });
                    } else {
                        $('#formTambahJadwal')[0].submit();
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error!', 'Gagal memeriksa konflik jadwal.', 'error');
                }
            });
        });
    });
</script>
@endsection
