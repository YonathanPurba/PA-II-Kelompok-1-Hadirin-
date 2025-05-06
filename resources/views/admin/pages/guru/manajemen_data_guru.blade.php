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
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <form method="GET" action="{{ route('guru.index') }}" class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-2">
                                <label for="mata_pelajaran" class="me-2 mb-0">Mata Pelajaran:</label>
                                <select name="mata_pelajaran" id="mata_pelajaran" class="form-select">
                                    <option value="">Semua Mata Pelajaran</option>
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
                                <select name="status" id="status" class="form-select">
                                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="aktif" {{ (request('status') == 'aktif' || (!request()->has('status'))) ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-outline-success">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>
                            
                            @if(request()->has('mata_pelajaran') || request()->has('status'))
                                <a href="{{ route('guru.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Reset
                                </a>
                            @endif
                        </form>

                        <!-- Export Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('guru.export.pdf', ['mata_pelajaran' => request('mata_pelajaran'), 'status' => request('status')]) }}" 
                               class="btn btn-danger">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
                            </a>
                            <a href="{{ route('guru.export.excel', ['mata_pelajaran' => request('mata_pelajaran'), 'status' => request('status')]) }}" 
                               class="btn btn-success">
                                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
                            </a>
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
                                                {{ $guru->bidang_studi ?? '-' }}
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

                    <div class="mt-4 text-end">
                        <a href="{{ url('guru/create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Guru
                        </a>
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

                        <dt class="col-sm-4">Bidang Studi</dt>
                        <dd class="col-sm-8" id="view-bidang-studi">-</dd>
                        
                        <dt class="col-sm-4">Mata Pelajaran</dt>
                        <dd class="col-sm-8" id="view-mata-pelajaran">-</dd>
                        
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8" id="view-status">-</dd>

                        <dt class="col-sm-4">Terakhir Login</dt>
                        <dd class="col-sm-8" id="view-terakhir-login">-</dd>

                        <dt class="col-sm-4">Jumlah Jadwal Mengajar</dt>
                        <dd class="col-sm-8" id="view-jadwal">-</dd>
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
                    <a id="btn-edit-guru" href="#" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // DataTable Initialization
        $('#guruTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            columnDefs: [
                { orderable: false, targets: [5] }, // Disable sorting on action column
                { searchable: false, targets: [0, 5] } // Disable searching on number and action columns
            ]
        });

        // Handle View Modal
        $('.btn-view-guru').on('click', function() {
            const guruId = $(this).data('id');
            
            // Update edit button href
            $('#btn-edit-guru').attr('href', `{{ url('guru') }}/${guruId}/edit`);
            
            // Reset modal content
            $('#view-nama-lengkap, #view-nip, #view-telepon, #view-bidang-studi, #view-mata-pelajaran, #view-status, #view-terakhir-login, #view-jadwal').text('-');
            $('#table-jadwal-body').html('<tr><td colspan="5" class="text-center">Memuat data...</td></tr>');
            
            // Fetch guru details via AJAX
            $.ajax({
                url: `{{ url('api/guru') }}/${guruId}`,
                method: 'GET',
                success: function(response) {
                    const guru = response.data;
                    
                    // Set basic info
                    $('#view-nama-lengkap').text(guru.nama_lengkap || '-');
                    $('#view-nip').text(guru.nip || '-');
                    $('#view-telepon').text(guru.nomor_telepon || '-');
                    $('#view-bidang-studi').text(guru.bidang_studi || '-');
                    
                    // Set mata pelajaran info
                    if (guru.mata_pelajaran && guru.mata_pelajaran.length > 0) {
                        const mapelNames = guru.mata_pelajaran.map(m => m.nama).join(', ');
                        $('#view-mata-pelajaran').text(mapelNames);
                    }
                    
                    // Set status with badge
                    if (guru.status === 'aktif') {
                        $('#view-status').html('<span class="badge bg-success">Aktif</span>');
                    } else {
                        $('#view-status').html('<span class="badge bg-secondary">Non-Aktif</span>');
                    }
                    
                    // Set login info
                    if (guru.user && guru.user.last_login_at) {
                        const lastLogin = new Date(guru.user.last_login_at);
                        $('#view-terakhir-login').text(lastLogin.toLocaleString('id-ID'));
                    }
                    
                    // Set jadwal info
                    if (guru.jadwal && guru.jadwal.length > 0) {
                        $('#view-jadwal').text(guru.jadwal.length);
                        
                        // Populate jadwal table
                        let jadwalHtml = '';
                        guru.jadwal.forEach((jadwal, index) => {
                            // Format hari with first letter capitalized
                            const hari = jadwal.hari ? jadwal.hari.charAt(0).toUpperCase() + jadwal.hari.slice(1) : '-';
                            
                            jadwalHtml += `
                                <tr>
                                    <td class="text-center">${index + 1}</td>
                                    <td>${hari}</td>
                                    <td>${jadwal.waktu_mulai || '--:--'} - ${jadwal.waktu_selesai || '--:--'}</td>
                                    <td>${jadwal.kelas ? jadwal.kelas.nama_kelas : '-'}</td>
                                    <td>${jadwal.mata_pelajaran ? jadwal.mata_pelajaran.nama : '-'}</td>
                                </tr>
                            `;
                        });
                        $('#table-jadwal-body').html(jadwalHtml);
                    } else {
                        $('#view-jadwal').text('0');
                        $('#table-jadwal-body').html('<tr><td colspan="5" class="text-center">Tidak ada jadwal mengajar</td></tr>');
                    }
                },
                error: function() {
                    alert('Gagal memuat data guru');
                }
            });
        });
        
        // Form filter responsive behavior
        $(window).on('resize', function() {
            adjustFilterFormLayout();
        });
        
        function adjustFilterFormLayout() {
            const filterForm = $('form[action="{{ route("guru.index") }}"]');
            const filterControls = filterForm.find('.d-flex.align-items-center.gap-2');
            
            if (window.innerWidth < 768) {
                filterForm.addClass('flex-column align-items-start').removeClass('align-items-center');
                filterControls.addClass('w-100');
                filterForm.find('select').addClass('w-100');
                filterForm.find('button[type="submit"]').addClass('w-100 mt-2');
            } else {
                filterForm.removeClass('flex-column align-items-start').addClass('align-items-center');
                filterControls.removeClass('w-100');
                filterForm.find('select').removeClass('w-100');
                filterForm.find('button[type="submit"]').removeClass('w-100 mt-2');
            }
        }
        
        // Initialize responsive layout
        adjustFilterFormLayout();
    });
</script>
@endsection