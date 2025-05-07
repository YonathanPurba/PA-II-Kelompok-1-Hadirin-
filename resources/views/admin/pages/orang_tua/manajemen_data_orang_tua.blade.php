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
                            <div class="d-flex align-items-center gap-1">
                                <label for="kelas" class="form-label form-label-sm me-1 mb-0">Kelas:</label>
                                <select name="kelas" id="kelas" class="form-select form-select-sm">
                                    <option value="">Semua</option>
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
                                    <option value="aktif" {{ (request('status') == 'aktif' || (!request()->has('status') && !request()->has('kelas') && !request()->has('search'))) ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama/telepon..." 
                                       value="{{ request('search') }}">
                            </div>
                            
                            <button type="submit" class="btn btn-outline-success">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>
                            
                            @if(request()->has('kelas') || request()->has('status') || request()->has('search'))
                                <a href="{{ route('orang-tua.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Reset
                                </a>
                            @endif
                        </form>

                        <!-- Export Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('orang-tua.export.pdf', ['kelas' => request('kelas'), 'status' => request('status'), 'search' => request('search')]) }}" 
                               class="btn btn-danger">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
                            </a>
                            <a href="{{ route('orang-tua.export.excel', ['kelas' => request('kelas'), 'status' => request('status'), 'search' => request('search')]) }}" 
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
                                                {{ $orangTua->siswa->pluck('nama')->join(', ') }}
                                            @else
                                                <span class="text-muted">Belum ada data anak</span>
                                            @endif
                                        </td>
                                        <td>{{ $orangTua->nomor_telepon ?? '-' }}</td>
                                        <td>
                                            @if($orangTua->status == 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($orangTua->status == 'nonaktif')
                                                <span class="badge bg-secondary">Non-Aktif</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
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
                                                    title="Lihat">
                                                    <i class="bi bi-eye-fill fs-5"></i>
                                                </a>

                                                <a href="{{ route('orang-tua.edit', $orangTua->id_orangtua) }}"
                                                    class="text-warning" title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>
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
                        
                        <!-- Pagination -->
                        @if($orangTuaList instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="d-flex justify-content-center mt-3">
                                {{ $orangTuaList->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Tombol Tambah -->
                    <div class="mt-4 text-end">
                        <a href="{{ route('orang-tua.create') }}" class="btn btn-success">
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="modal-title" id="modalViewOrangtuaLabel">Detail Orang Tua</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <dl class="row mb-4">
                        <dt class="col-sm-4">Nama Lengkap</dt>
                        <dd class="col-sm-8" id="view-nama-lengkap">-</dd>

                        <dt class="col-sm-4">Nomor Telepon</dt>
                        <dd class="col-sm-8" id="view-telepon">-</dd>

                        <dt class="col-sm-4">Pekerjaan</dt>
                        <dd class="col-sm-8" id="view-pekerjaan">-</dd>
                        
                        <dt class="col-sm-4">Alamat</dt>
                        <dd class="col-sm-8" id="view-alamat">-</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8" id="view-status">-</dd>
                    </dl>

                    <hr>

                    <h5 class="mb-3">Data Anak</h5>
<div class="table-responsive">
    <table class="table table-bordered table-sm align-middle">
        <thead class="table-light text-center">
            <tr>
                <th>No</th>
                <th>Nama Anak</th>
                <th>Kelas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="table-anak-body">
            <tr>
                <td colspan="4" class="text-center">Memuat data...</td>
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
        if (!$.fn.DataTable.isDataTable('#orangtuaTable')) {
            // Initialize DataTable only if it's not already initialized
            $('#orangtuaTable').DataTable({
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

        // Handle view orangtua button click
        $('.btn-view-orangtua').on('click', function() {
            const id = $(this).data('id');
            
            // Show loading state
            $('#view-nama-lengkap').text('Memuat...');
            $('#view-telepon').text('Memuat...');
            $('#view-pekerjaan').text('Memuat...');
            $('#view-alamat').text('Memuat...');
            $('#view-status').text('Memuat...');
            $('#table-anak-body').html('<tr><td colspan="4" class="text-center">Memuat data...</td></tr>');
            
            // Mengambil data secara langsung dari atribut data
            // Ini adalah solusi sementara jika endpoint AJAX tidak berfungsi
            const parentData = {
                nama_lengkap: $(this).data('nama') || '-',
                nomor_telepon: $(this).data('nomor') || '-',
                pekerjaan: $(this).data('pekerjaan') || '-',
                alamat: $(this).data('alamat') || '-',
                status: $(this).data('status') || 'pending',
            };

            // Fill in basic details dari atribut data
            $('#view-nama-lengkap').text(parentData.nama_lengkap);
            $('#view-telepon').text(parentData.nomor_telepon);
            $('#view-pekerjaan').text(parentData.pekerjaan);
            $('#view-alamat').text(parentData.alamat);
            
            // Set status with badge
            if (parentData.status === 'aktif') {
                $('#view-status').html('<span class="badge bg-success">Aktif</span>');
            } else if (parentData.status === 'nonaktif') {
                $('#view-status').html('<span class="badge bg-secondary">Non-Aktif</span>');
            } else {
                $('#view-status').html('<span class="badge bg-warning">Pending</span>');
            }
            
            // Coba ambil data anak via AJAX
            $.ajax({
                url: `/orang-tua/${id}/anak`,
                method: 'GET',
                success: function(response) {
                    // Fill in children table
                    if (response && response.length > 0) {
                        let tableContent = '';
                        response.forEach((anak, index) => {
                            // Create status badge based on child's status
                            let statusBadge = '';
                            if (anak.status === 'aktif') {
                                statusBadge = '<span class="badge bg-success">Aktif</span>';
                            } else if (anak.status === 'nonaktif') {
                                statusBadge = '<span class="badge bg-secondary">Non-Aktif</span>';
                            } else {
                                statusBadge = '<span class="badge bg-warning">Pending</span>';
                            }
                            
                            tableContent += `
                                <tr>
                                    <td class="text-center">${index + 1}</td>
                                    <td class="text-center">${anak.nama}</td>
                                    <td class="text-center">${anak.kelas ? anak.kelas.nama_kelas : 'Belum ada kelas'}</td>
                                    <td class="text-center">${statusBadge}</td>
                                </tr>
                            `;
                        });
                        $('#table-anak-body').html(tableContent);
                    } else {
                        $('#table-anak-body').html('<tr><td colspan="4" class="text-center">Belum ada data anak</td></tr>');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching children data:', xhr);
                    $('#table-anak-body').html('<tr><td colspan="4" class="text-center">Belum ada data anak</td></tr>');
                }
            });
        });
    });
</script>
@endsection 