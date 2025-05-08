@extends('layouts.admin-layout')

@section('title', 'Tambah Jadwal Pelajaran')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul mb-4">
                    <h1 class="mb-3">
                        <a href="{{ route('jadwal-pelajaran.index') }}" class="text-decoration-none text-success fw-semibold">
                            Manajemen Jadwal
                        </a>
                        <span class="fs-5 text-muted">/ Tambah Jadwal</span>
                    </h1>
                    <p class="mb-2">Halaman untuk menambahkan jadwal pelajaran baru</p>
                </header>

                <div class="data">
                    <!-- Informasi Bantuan -->
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i> <strong>Informasi:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Sistem akan otomatis memeriksa bentrok jadwal untuk guru dan kelas</li>
                            <li>Gunakan fitur "Cek Rekomendasi Jadwal" untuk mendapatkan saran waktu yang tersedia</li>
                            <li>Pastikan guru mengajar mata pelajaran yang sesuai dengan bidang keahliannya</li>
                        </ul>
                    </div>

                    <form action="{{ route('jadwal-pelajaran.store') }}" method="POST" class="p-4 pt-1 rounded-4 bg-white shadow-sm" id="formTambahJadwal">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Informasi Jadwal</h5>
                            </div>
                            
                            <!-- Kelas -->
                            <div class="col-md-6 mb-3">
                                <label for="id_kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select name="id_kelas" id="id_kelas" class="form-select @error('id_kelas') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}
                                            @if($k->tahunAjaran)
                                                ({{ $k->tahunAjaran->nama_tahun_ajaran }})
                                                @if($k->tahunAjaran->aktif)
                                                    - Aktif
                                                @else
                                                    - Non-Aktif
                                                @endif
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mata Pelajaran -->
                            <div class="col-md-6 mb-3">
                                <label for="id_mata_pelajaran" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select name="id_mata_pelajaran" id="id_mata_pelajaran" class="form-select @error('id_mata_pelajaran') is-invalid @enderror" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach ($mataPelajaran as $mp)
                                        <option value="{{ $mp->id_mata_pelajaran }}" {{ old('id_mata_pelajaran') == $mp->id_mata_pelajaran ? 'selected' : '' }}>
                                            {{ $mp->nama }} ({{ $mp->kode }})
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
                                    @foreach ($guru as $g)
                                        <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                            {{ $g->nama_lengkap }}
                                            @if($g->bidang_studi)
                                                ({{ $g->bidang_studi }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text mt-1" id="guru-mapel-info">
                                    <span class="text-info">Info:</span> Pilih guru yang mengajar mata pelajaran terkait.
                                </div>
                                @error('id_guru')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Hari -->
                            <div class="col-md-6 mb-3">
                                <label for="hari" class="form-label">Hari <span class="text-danger">*</span></label>
                                <select name="hari" id="hari" class="form-select @error('hari') is-invalid @enderror" required>
                                    <option value="">-- Pilih Hari --</option>
                                    @foreach ($schoolDays as $day)
                                        <option value="{{ $day }}" {{ old('hari') == $day ? 'selected' : '' }}>
                                            {{ ucfirst($day) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sesi -->
                            <div class="col-md-6 mb-3">
                                <label for="session" class="form-label">Sesi <span class="text-danger">*</span></label>
                                <select name="session" id="session" class="form-select @error('session') is-invalid @enderror" required>
                                    <option value="">-- Pilih Sesi --</option>
                                    @foreach ($timeSlots as $index => $slot)
                                        <option value="{{ $index + 1 }}" {{ old('session') == ($index + 1) ? 'selected' : '' }}>
                                            {{ $slot['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('session')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tahun Ajaran -->
                            <div class="col-md-6 mb-3">
                                <label for="id_tahun_ajaran" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                                <input type="hidden" name="id_tahun_ajaran" value="{{ $tahunAjaranAktif->id_tahun_ajaran }}">
                                <input type="text" class="form-control" value="{{ $tahunAjaranAktif->nama_tahun_ajaran }} (Aktif)" readonly>
                            </div>
                        </div>

                        <!-- Tombol Cek Rekomendasi Jadwal -->
                        <div class="mb-4">
                            <button type="button" id="btnCekRekomendasi" class="btn btn-outline-primary">
                                <i class="bi bi-calendar-check me-1"></i> Cek Rekomendasi Jadwal
                            </button>
                            <div id="rekomendasiJadwalContainer" class="mt-3 d-none">
                                <h6>Rekomendasi Sesi Tersedia:</h6>
                                <div id="rekomendasiJadwalList" class="list-group">
                                    <!-- Akan diisi oleh JavaScript -->
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('jadwal-pelajaran.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>

                    <!-- Jadwal Existing -->
                    <div class="mt-4 p-4 rounded-4 bg-white shadow-sm">
                        <h5 class="border-bottom pb-2 mb-3">Jadwal Yang Sudah Ada</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">Filter:</span>
                                    <select id="filterJadwal" class="form-select">
                                        <option value="all">Semua Jadwal</option>
                                        <option value="guru">Berdasarkan Guru</option>
                                        <option value="kelas">Berdasarkan Kelas</option>
                                        <option value="hari">Berdasarkan Hari</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="filterContainer" class="input-group d-none">
                                    <!-- Akan diisi oleh JavaScript -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="jadwalExistingTable" class="table table-striped table-bordered table-sm">
                                <thead class="bg-success text-white">
                                    <tr>
                                        <th>Hari</th>
                                        <th>Waktu</th>
                                        <th>Kelas</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jadwalExisting as $jadwal)
                                        <tr 
                                            data-guru="{{ $jadwal->id_guru }}" 
                                            data-kelas="{{ $jadwal->id_kelas }}" 
                                            data-hari="{{ $jadwal->hari }}"
                                        >
                                            <td>{{ ucfirst($jadwal->hari) }}</td>
                                            <td>{{ date('H:i', strtotime($jadwal->waktu_mulai)) }} - {{ date('H:i', strtotime($jadwal->waktu_selesai)) }}</td>
                                            <td>{{ $jadwal->kelas->nama_kelas ?? '-' }}</td>
                                            <td>{{ $jadwal->mataPelajaran->nama ?? '-' }}</td>
                                            <td>{{ $jadwal->guru->nama_lengkap ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        // Initialize select2 for better dropdown experience
        $('#id_kelas, #id_mata_pelajaran, #id_guru, #hari, #session, #status').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
        
        // DataTable untuk jadwal yang sudah ada
        const jadwalTable = $('#jadwalExistingTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json',
            },
            pageLength: 5,
            lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "Semua"]],
            order: [[0, 'asc'], [1, 'asc']]
        });
        
        // Filter jadwal
        $('#filterJadwal').on('change', function() {
            const filterType = $(this).val();
            const filterContainer = $('#filterContainer');
            
            filterContainer.empty().addClass('d-none');
            
            if (filterType === 'all') {
                jadwalTable.search('').columns().search('').draw();
                return;
            }
            
            filterContainer.removeClass('d-none');
            
            if (filterType === 'guru') {
                const guruSelect = $('<select id="filterGuru" class="form-select"></select>');
                guruSelect.append('<option value="">Semua Guru</option>');
                
                // Ambil semua guru dari tabel
                const guruSet = new Set();
                jadwalTable.rows().every(function() {
                    const guru = $(this.node()).find('td:eq(4)').text();
                    if (guru && guru !== '-') {
                        guruSet.add(guru);
                    }
                });
                
                // Tambahkan opsi guru
                Array.from(guruSet).sort().forEach(guru => {
                    guruSelect.append(`<option value="${guru}">${guru}</option>`);
                });
                
                filterContainer.append('<span class="input-group-text">Guru:</span>').append(guruSelect);
                
                guruSelect.on('change', function() {
                    const selectedGuru = $(this).val();
                    jadwalTable.column(4).search(selectedGuru).draw();
                });
            } else if (filterType === 'kelas') {
                const kelasSelect = $('<select id="filterKelas" class="form-select"></select>');
                kelasSelect.append('<option value="">Semua Kelas</option>');
                
                // Ambil semua kelas dari tabel
                const kelasSet = new Set();
                jadwalTable.rows().every(function() {
                    const kelas = $(this.node()).find('td:eq(2)').text();
                    if (kelas && kelas !== '-') {
                        kelasSet.add(kelas);
                    }
                });
                
                // Tambahkan opsi kelas
                Array.from(kelasSet).sort().forEach(kelas => {
                    kelasSelect.append(`<option value="${kelas}">${kelas}</option>`);
                });
                
                filterContainer.append('<span class="input-group-text">Kelas:</span>').append(kelasSelect);
                
                kelasSelect.on('change', function() {
                    const selectedKelas = $(this).val();
                    jadwalTable.column(2).search(selectedKelas).draw();
                });
            } else if (filterType === 'hari') {
                const hariSelect = $('<select id="filterHari" class="form-select"></select>');
                hariSelect.append('<option value="">Semua Hari</option>');
                
                // Tambahkan opsi hari
                const hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                hariList.forEach(hari => {
                    hariSelect.append(`<option value="${hari}">${hari}</option>`);
                });
                
                filterContainer.append('<span class="input-group-text">Hari:</span>').append(hariSelect);
                
                hariSelect.on('change', function() {
                    const selectedHari = $(this).val();
                    jadwalTable.column(0).search(selectedHari).draw();
                });
            }
        });
        
        // Cek rekomendasi jadwal
        $('#btnCekRekomendasi').on('click', function() {
            const idGuru = $('#id_guru').val();
            const idKelas = $('#id_kelas').val();
            const hari = $('#hari').val();
            
            if (!idGuru || !idKelas || !hari) {
                Swal.fire({
                    title: 'Informasi Tidak Lengkap',
                    text: 'Silakan pilih guru, kelas, dan hari terlebih dahulu',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Tampilkan loading
            Swal.fire({
                title: 'Mencari Rekomendasi...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Ambil rekomendasi jadwal
            $.ajax({
                url: "{{ route('jadwal-pelajaran.rekomendasi') }}",
                method: 'GET',
                data: {
                    id_guru: idGuru,
                    id_kelas: idKelas,
                    hari: hari
                },
                success: function(response) {
                    Swal.close();
                    
                    if (response.success) {
                        const rekomendasiList = $('#rekomendasiJadwalList');
                        rekomendasiList.empty();
                        
                        if (response.data.length > 0) {
                            response.data.forEach(slot => {
                                rekomendasiList.append(`
                                    <button type="button" class="list-group-item list-group-item-action btn-pilih-jadwal"
                                        data-session="${slot.session}">
                                        <i class="bi bi-clock me-2"></i>
                                        ${slot.label}
                                    </button>
                                `);
                            });
                            
                            $('#rekomendasiJadwalContainer').removeClass('d-none');
                        } else {
                            Swal.fire({
                                title: 'Tidak Ada Rekomendasi',
                                text: 'Tidak ditemukan slot waktu yang tersedia untuk guru dan kelas pada hari yang dipilih',
                                icon: 'info',
                                confirmButtonText: 'OK'
                            });
                        }
                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengambil rekomendasi jadwal',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
        
        // Pilih jadwal dari rekomendasi
        $(document).on('click', '.btn-pilih-jadwal', function() {
            const session = $(this).data('session');
            
            $('#session').val(session).trigger('change');
            
            // Highlight tombol yang dipilih
            $('.btn-pilih-jadwal').removeClass('active');
            $(this).addClass('active');
        });
        
        // Tampilkan info mata pelajaran yang diajarkan guru
        $('#id_guru').on('change', function() {
            const idGuru = $(this).val();
            
            if (idGuru) {
                $.ajax({
                    url: `/guru/${idGuru}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.mata_pelajaran) {
                            $('#guru-mapel-info').html(`
                                <span class="text-info">Info:</span> Guru ini mengajar: <strong>${response.mata_pelajaran}</strong>
                            `);
                        } else {
                            $('#guru-mapel-info').html(`
                                <span class="text-warning">Perhatian:</span> Guru ini belum memiliki mata pelajaran yang diampu.
                            `);
                        }
                    },
                    error: function() {
                        $('#guru-mapel-info').html(`
                            <span class="text-info">Info:</span> Pilih guru yang mengajar mata pelajaran terkait.
                        `);
                    }
                });
            } else {
                $('#guru-mapel-info').html(`
                    <span class="text-info">Info:</span> Pilih guru yang mengajar mata pelajaran terkait.
                `);
            }
        });
    });
</script>
@endsection
