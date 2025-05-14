@extends('layouts.admin-layout')

@section('title', 'Manajemen Data Guru')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Data Guru</h1>
                    <p class="mb-2">Filter data guru berdasarkan mata pelajaran dan status</p>
                </header>
<div class="data">
    <!-- Filter Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-nowrap">
        <form method="GET" action="{{ route('guru.index') }}" class="d-flex align-items-center gap-3 flex-nowrap">
            <div class="d-flex align-items-center gap-2">
                <label for="mata_pelajaran" class="form-label form-label-md me-2 mb-0">Mapel:</label>
                <select name="mata_pelajaran" id="mata_pelajaran" class="form-select form-select-md" style="min-width: 140px; max-width: 160px;">
                    <option value="">Semua</option>
                    @foreach ($mataPelajaranList as $mapel)
                        <option value="{{ $mapel->id_mata_pelajaran }}"
                            {{ request('mata_pelajaran') == $mapel->id_mata_pelajaran ? 'selected' : '' }}>
                            {{ $mapel->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex align-items-center gap-2">
                <label for="status" class="me-2 mb-0">Status:</label>
                <select name="status" id="status" class="form-select form-select-md" style="min-width: 140px; max-width: 160px;">
                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="aktif" {{ (request('status') == 'aktif' || !request()->has('status')) ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <input type="text" name="search" class="form-control form-control-md" placeholder="Cari nama/NIP..." 
                   value="{{ request('search') }}" style="width: 220px; min-width: 180px;">

            <button type="submit" class="btn btn-outline-success btn-md d-flex align-items-center gap-2">
                <i class="bi bi-filter"></i> Filter
            </button>

            @if(request()->has('mata_pelajaran') || request()->has('status') || request()->has('search'))
                <a href="{{ route('guru.index') }}" class="btn btn-outline-secondary btn-md d-flex align-items-center gap-2">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            @endif
        </form>

        <!-- Export Buttons -->
        <div class="d-flex gap-3 flex-nowrap">
            <a href="{{ route('guru.export.pdf', ['mata_pelajaran' => request('mata_pelajaran'), 'status' => request('status'), 'search' => request('search')]) }}" 
               class="btn btn-danger btn-md d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
            </a>
            <a href="{{ route('guru.export.excel', ['mata_pelajaran' => request('mata_pelajaran'), 'status' => request('status'), 'search' => request('search')]) }}" 
               class="btn btn-success btn-md d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-excel-fill"></i> Excel
            </a>
            <a href="{{ url('guru/create') }}" class="btn btn-success btn-md d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
        </div>
    </div>
</div>



                    <!-- Tabel Data Guru -->
                    <div class="table-responsive">
                        <table id="guruTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Nama Lengkap</th>
                                    <th width="15%">NIP</th>
                                    <th width="20%">Mata Pelajaran</th>
                                    <th width="15%">Status</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($gurus as $index => $guru)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $guru->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $guru->nip ?? '-' }}</td>
                                        <td>
                                            @if($guru->mataPelajaran && $guru->mataPelajaran->count() > 0)
                                                {{ $guru->mataPelajaran->pluck('nama')->join(', ') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($guru->status == 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="javascript:void(0);" class="text-primary btn-view-guru"
                                                    data-id="{{ $guru->id_guru }}" data-bs-toggle="modal"
                                                    data-bs-target="#modalViewGuru" title="Lihat">
                                                    <i class="bi bi-eye-fill fs-5"></i>
                                                </a>

                                                <a href="{{ url('guru/' . $guru->id_guru . '/edit') }}" class="text-warning"
                                                    title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">Tidak ada data guru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal View Guru -->
    <div class="modal fade" id="modalViewGuru" tabindex="-1" aria-labelledby="modalViewGuruLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="modal-title" id="modalViewGuruLabel">Detail Guru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <dl class="row mb-4">
                        <dt class="col-sm-4">Nama Lengkap</dt>
                        <dd class="col-sm-8" id="view-nama-lengkap">-</dd>

                        <dt class="col-sm-4">NIP</dt>
                        <dd class="col-sm-8" id="view-nip">-</dd>

                        <dt class="col-sm-4">Nomor Telepon</dt>
                        <dd class="col-sm-8" id="view-telepon">-</dd>
                        
                        <dt class="col-sm-4">Mata Pelajaran</dt>
                        <dd class="col-sm-8">
                            <span id="view-mata-pelajaran" class="fw-medium">-</span>
                        </dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8" id="view-status">-</dd>

                        <dt class="col-sm-4">Jumlah Jadwal Mengajar</dt>
                        <dd class="col-sm-8" id="view-jadwal">-</dd>

                        <dt class="col-sm-4">Wali Kelas</dt>
                        <dd class="col-sm-8" id="view-wali-kelas">-</dd>
                    </dl>

                    <hr>

                    <h5 class="mb-3">Jadwal Mengajar</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Hari</th>
                                    <th>Waktu</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                </tr>
                            </thead>
                            <tbody id="table-jadwal-body">
                                <tr>
                                    <td colspan="5" class="text-center">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script>
    $(document).ready(function() {
        // Check if DataTable is already initialized
        if (!$.fn.DataTable.isDataTable('#guruTable')) {
            // Initialize DataTable only if it's not already initialized
            $('#guruTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json',
                },
                // Disable sorting on the last column (actions)
                "columnDefs": [
                    { "orderable": false, "targets": 5 }
                ],
                // Disable pagination since we're using Laravel's pagination
                "paging": false,
                // Disable the default search box since we have our own filter
                "searching": false,
                // Disable the info display since we're using Laravel's pagination
                "info": false
            });
        }

        // Handle view guru button click
        $('.btn-view-guru').on('click', function() {
            const id = $(this).data('id');
            
            // Show loading state
            $('#view-nama-lengkap').text('Memuat...');
            $('#view-nip').text('Memuat...');
            $('#view-telepon').text('Memuat...');
            $('#view-mata-pelajaran').text('Memuat...');
            $('#view-status').text('Memuat...');
            $('#view-jadwal').text('Memuat...');
            $('#view-wali-kelas').text('Memuat...');
            $('#table-jadwal-body').html('<tr><td colspan="5" class="text-center">Memuat data...</td></tr>');
            
            // Fetch guru details
            $.ajax({
                url: `/guru/${id}`,
                method: 'GET',
                success: function(response) {
                    // Fill in basic details
                    $('#view-nama-lengkap').text(response.nama_lengkap);
                    $('#view-nip').text(response.nip);
                    $('#view-telepon').text(response.nomor_telepon);
                    $('#view-mata-pelajaran').text(response.mata_pelajaran);
                    
                    // Set status with badge
                    if (response.status === 'aktif') {
                        $('#view-status').html('<span class="badge bg-success">Aktif</span>');
                    } else {
                        $('#view-status').html('<span class="badge bg-secondary">Non-Aktif</span>');
                    }
                    
                    $('#view-jadwal').text(response.jumlah_jadwal);
                    
                    // Display wali kelas information
                    if (response.wali_kelas && response.wali_kelas.length > 0) {
                        let waliKelasText = '';
                        response.wali_kelas.forEach((kelas, index) => {
                            const statusBadge = kelas.status_tahun_ajaran === 'Aktif' 
                                ? '<span class="badge bg-success ms-1">Aktif</span>' 
                                : '<span class="badge bg-secondary ms-1">Tidak Aktif</span>';
                            
                            waliKelasText += `${kelas.nama_kelas} (${kelas.tahun_ajaran}) ${statusBadge}`;
                            
                            if (index < response.wali_kelas.length - 1) {
                                waliKelasText += '<br>';
                            }
                        });
                        $('#view-wali-kelas').html(waliKelasText);
                    } else {
                        $('#view-wali-kelas').text('Bukan Wali Kelas');
                    }
                    
                    // Fill in schedule table
                    if (response.jadwal && response.jadwal.length > 0) {
                        let tableContent = '';
                        response.jadwal.forEach((jadwal, index) => {
                            tableContent += `
                                <tr>
                                    <td class="text-center">${index + 1}</td>
                                    <td>${jadwal.hari}</td>
                                    <td class="text-center">${jadwal.waktu_mulai} - ${jadwal.waktu_selesai}</td>
                                    <td>${jadwal.kelas}</td>
                                    <td>${jadwal.mata_pelajaran}</td>
                                </tr>
                            `;
                        });
                        $('#table-jadwal-body').html(tableContent);
                    } else {
                        $('#table-jadwal-body').html('<tr><td colspan="5" class="text-center">Tidak ada jadwal mengajar</td></tr>');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching guru details:', xhr);
                    $('#table-jadwal-body').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>');
                    
                    // Show error notification
                    const errorMessage = xhr.status === 404 
                        ? 'Data guru tidak ditemukan' 
                        : 'Terjadi kesalahan saat memuat data';
                    
                    // You can implement a toast notification here
                    alert(errorMessage);
                }
            });
        });
    });
</script>
@endsection
