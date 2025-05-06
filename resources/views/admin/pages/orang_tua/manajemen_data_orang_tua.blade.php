@extends('layouts.admin-layout')

@section('title', 'Data Orang Tua')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Data Orang Tua</h1>
                    <p class="mb-2">Filter data orang tua berdasarkan kelas anak dan status</p>
                </header>
                <div class="data">
                    <!-- Filter Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <form method="GET" action="{{ route('orang-tua.index') }}" class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-2">
                                <label for="kelas" class="me-2 mb-0">Kelas:</label>
                                <select name="kelas" id="kelas" class="form-select">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id_kelas }}" {{ request('kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                            {{ $kelas->nama_kelas }}
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
                            
                            @if(request()->has('kelas') || request()->has('status'))
                                <a href="{{ route('orang-tua.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Reset
                                </a>
                            @endif
                        </form>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('orang-tua.export.pdf', ['kelas' => request('kelas'), 'status' => request('status')]) }}" 
                               class="btn btn-danger">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
                            </a>
                            <a href="{{ route('orang-tua.export.excel', ['kelas' => request('kelas'), 'status' => request('status')]) }}" 
                               class="btn btn-success">
                                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
                            </a>
                        </div>
                    </div>

                    <!-- Tabel Orang Tua -->
                    <div class="table-responsive">
                        <table id="orangtuaTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Nama Orang Tua</th>
                                    <th width="30%">Nama Anak</th>
                                    <th width="15%">Nomor Telepon</th>
                                    <th width="10%">Status</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orangTuaList as $index => $orangTua)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $orangTua->nama_lengkap }}</td>
                                        <td>
                                            @if($orangTua->siswa->count() > 0)
                                                <ul class="mb-0 ps-3">
                                                    @foreach ($orangTua->siswa as $anak)
                                                        <li>{{ $anak->nama }} ({{ $anak->kelas->nama_kelas ?? 'Belum ada kelas' }})</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">Belum ada data anak</span>
                                            @endif
                                        </td>
                                        <td>{{ $orangTua->nomor_telepon ?? '-' }}</td>
                                        <td>
                                            @if($orangTua->status == 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="javascript:void(0);" class="text-primary btn-view-orangtua"
                                                    data-id="{{ $orangTua->id_orangtua }}"
                                                    data-nama="{{ $orangTua->nama_lengkap }}"
                                                    data-alamat="{{ $orangTua->alamat ?? '-' }}"
                                                    data-pekerjaan="{{ $orangTua->pekerjaan ?? '-' }}"
                                                    data-nomor="{{ $orangTua->nomor_telepon ?? '-' }}"
                                                    data-status="{{ $orangTua->status }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalViewOrangtua"
                                                    title="Lihat Detail">
                                                    <i class="bi bi-eye-fill fs-5"></i>
                                                </a>

                                                <a href="{{ route('orang-tua.edit', $orangTua->id_orangtua) }}"
                                                    class="text-warning" title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>

                                                <!-- Uncomment if delete functionality is needed
                                                <a href="javascript:void(0);" class="text-danger btn-delete-orangtua" 
                                                    data-id="{{ $orangTua->id_orangtua }}"
                                                    data-nama="{{ $orangTua->nama_lengkap }}"
                                                    data-jumlah-anak="{{ $orangTua->siswa->count() }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalDeleteOrangtua"
                                                    title="Hapus">
                                                    <i class="bi bi-trash-fill fs-5"></i>
                                                </a>
                                                -->
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">Tidak ada data orang tua.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <!-- Add pagination if needed -->
                        @if($orangTuaList instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="d-flex justify-content-center mt-3">
                                {{ $orangTuaList->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Tombol Tambah (Moved to match siswa.index) -->
                    <div class="mt-4 text-end">
                        <a href="{{ route('orang-tua.create') }}" class="btn btn-success px-4">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Orang Tua
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal View Orang Tua -->
    <div class="modal fade" id="modalViewOrangtua" tabindex="-1" aria-labelledby="modalViewOrangtuaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="modal-title" id="modalViewOrangtuaLabel">Detail Orang Tua</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong class="d-block mb-1">Nama Lengkap:</strong>
                                <p id="modal-nama" class="mb-0 ps-2">-</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong class="d-block mb-1">Nomor Telepon:</strong>
                                <p id="modal-nomor" class="mb-0 ps-2">-</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong class="d-block mb-1">Pekerjaan:</strong>
                                <p id="modal-pekerjaan" class="mb-0 ps-2">-</p>
                            </div>
                            
                            <div class="mb-3">
                                <strong class="d-block mb-1">Status:</strong>
                                <p id="modal-status-container" class="mb-0 ps-2">
                                    <span id="modal-status-badge" class="badge">-</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <strong class="d-block mb-1">Alamat:</strong>
                            <p id="modal-alamat" class="mb-0 ps-2">-</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <strong class="d-block mb-1">Data Anak:</strong>
                            <div id="modal-anak-container" class="ps-2">
                                <ul id="modal-anak" class="mb-0 ps-3">
                                    <!-- Anak-anak akan diisi via JS -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="btn-edit-orangtua" href="#" class="btn btn-warning">
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
        // DataTable Initialization with improved options
        $('#orangtuaTable').DataTable({
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
        $('.btn-view-orangtua').on('click', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama') || '-';
            const alamat = $(this).data('alamat') || '-';
            const pekerjaan = $(this).data('pekerjaan') || '-';
            const nomor = $(this).data('nomor') || '-';
            const status = $(this).data('status') || '-';
            
            // Update edit button href
            $('#btn-edit-orangtua').attr('href', `{{ url('orang-tua') }}/${id}/edit`);
            
            // Set basic info
            $('#modal-nama').text(nama);
            $('#modal-alamat').text(alamat);
            $('#modal-pekerjaan').text(pekerjaan);
            $('#modal-nomor').text(nomor);
            
            // Set status with badge
            const statusBadge = $('#modal-status-badge');
            if (status === 'aktif') {
                statusBadge.text('Aktif').removeClass('bg-secondary').addClass('bg-success');
            } else {
                statusBadge.text('Non-Aktif').removeClass('bg-success').addClass('bg-secondary');
            }
            
            // Get children data via AJAX for the most up-to-date information
            const anakList = $('#modal-anak');
            anakList.empty().html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');
            
            // AJAX request to get children data
            $.ajax({
                url: `{{ url('api/orang-tua') }}/${id}/anak`,
                method: 'GET',
                success: function(response) {
                    anakList.empty();
                    
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(anak => {
                            const kelasInfo = anak.kelas ? `(${anak.kelas.nama_kelas})` : '(Belum ada kelas)';
                            anakList.append(`<li>${anak.nama} ${kelasInfo}</li>`);
                        });
                    } else {
                        anakList.append('<li class="text-muted">Belum ada data anak</li>');
                    }
                },
                error: function() {
                    anakList.empty().append('<li class="text-danger">Gagal memuat data anak</li>');
                }
            });
        });
        
        // Form filter responsive behavior
        $(window).on('resize', function() {
            adjustFilterFormLayout();
        });
        
        function adjustFilterFormLayout() {
            const filterForm = $('form[action="{{ route("orang-tua.index") }}"]');
            if (window.innerWidth < 768) {
                filterForm.addClass('flex-column align-items-start').removeClass('align-items-center');
                filterForm.find('div').addClass('w-100');
                filterForm.find('select').addClass('w-100');
            } else {
                filterForm.removeClass('flex-column align-items-start').addClass('align-items-center');
                filterForm.find('div').removeClass('w-100');
                filterForm.find('select').removeClass('w-100');
            }
        }
        
        // Initialize responsive layout
        adjustFilterFormLayout();
    });
</script>
@endsection