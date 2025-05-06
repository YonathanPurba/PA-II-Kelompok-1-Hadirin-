@extends('layouts.admin-layout')

@section('title', 'Data Siswa')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Data Siswa</h1>
                    <p class="mb-2">Filter data siswa berdasarkan kelas dan status</p>
                </header>

                <div class="data">
                    <!-- Filter & Export Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <form method="GET" action="{{ route('siswa.index') }}" class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-2">
                                <label for="kelas" class="me-2 mb-0">Kelas:</label>
                                <select name="kelas" id="kelas" class="form-select">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id_kelas }}"
                                            {{ request('kelas') == $kelas->id_kelas ? 'selected' : '' }}>
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
                                <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Reset
                                </a>
                            @endif
                        </form>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('siswa.export.pdf', ['kelas' => request('kelas'), 'status' => request('status')]) }}"
                                class="btn btn-danger">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
                            </a>
                            <a href="{{ route('siswa.export.excel', ['kelas' => request('kelas'), 'status' => request('status')]) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
                            </a>
                        </div>
                    </div>

                    <!-- Tabel Siswa -->
                    <div class="table-responsive">
                        <table id="siswaTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Nama Siswa</th>
                                    <th width="20%">Kelas</th>
                                    <th width="15%">Jenis Kelamin</th>
                                    <th width="15%">Status</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswaList as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $siswa->nama ?? '-' }}</td>
                                        <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                                        <td>{{ $siswa->jenis_kelamin ?? '-' }}</td>
                                        <td>
                                            @if($siswa->status == 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                {{-- Tombol View --}}
                                                <a href="javascript:void(0);" class="text-primary btn-view-siswa"
                                                    data-siswa='@json($siswa)' data-bs-toggle="modal"
                                                    data-bs-target="#modalViewSiswa" title="Lihat">
                                                    <i class="bi bi-eye-fill fs-5"></i>
                                                </a>

                                                <!-- Edit Button -->
                                                <a href="{{ url('siswa/' . $siswa->id_siswa . '/edit') }}"
                                                    class="text-warning" title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">Tidak ada data siswa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol Tambah -->
                    <div class="mt-4 text-end">
                        <a href="{{ route('siswa.create') }}" class="btn btn-success px-4">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Siswa
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal View Siswa -->
    <div class="modal fade" id="modalViewSiswa" tabindex="-1" aria-labelledby="modalViewSiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="modal-title" id="modalViewSiswaLabel">Detail Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nama Siswa:</strong>
                            <p id="viewNama" class="mb-2"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Jenis Kelamin:</strong>
                            <p id="viewGender" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>NISN:</strong>
                            <p id="viewNisn" class="mb-2"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Kelas:</strong>
                            <p id="viewKelas" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p id="viewStatus" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Alamat:</strong>
                            <p id="viewAlamat" class="mb-0"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="btn-edit-siswa" href="#" class="btn btn-warning">
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
        $('#siswaTable').DataTable({
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
        $('.btn-view-siswa').on('click', function() {
            const siswaData = $(this).data('siswa');
            
            // Update edit button href
            $('#btn-edit-siswa').attr('href', `{{ url('siswa') }}/${siswaData.id_siswa}/edit`);
            
            // Set basic info
            $('#viewNama').text(siswaData.nama || '-');
            $('#viewGender').text(siswaData.jenis_kelamin || '-');
            $('#viewNisn').text(siswaData.nisn || '-');
            $('#viewKelas').text(siswaData.kelas ? siswaData.kelas.nama_kelas : '-');
            $('#viewAlamat').text(siswaData.alamat || '-');
            
            // Set status with badge
            if (siswaData.status === 'aktif') {
                $('#viewStatus').html('<span class="badge bg-success">Aktif</span>');
            } else {
                $('#viewStatus').html('<span class="badge bg-secondary">Non-Aktif</span>');
            }
        });
        
        // Form filter responsive behavior
        $(window).on('resize', function() {
            adjustFilterFormLayout();
        });
        
        function adjustFilterFormLayout() {
            const filterForm = $('form[action="{{ route("siswa.index") }}"]');
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