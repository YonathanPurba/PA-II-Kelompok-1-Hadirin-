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
                                <label for="kelas" class="form-label form-label-sm me-2 mb-0">Kelas:</label>
                                <select name="kelas" id="kelas" class="form-select form-select-sm" style="min-width: 140px; max-width: 160px;">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id_kelas }}"
                                            {{ request('kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                            {{ $kelas->nama_kelas }}
                                            @if($kelas->tahunAjaran)
                                                ({{ $kelas->tahunAjaran->nama_tahun_ajaran }})
                                                @if($kelas->tahunAjaran->aktif)
                                                    - Aktif
                                                @endif
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="d-flex align-items-center gap-2">
                                <label for="status" class="me-2 mb-0">Status:</label>
                                <select name="status" id="status" class="form-select form-select-sm" style="min-width: 140px; max-width: 160px;">
                                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="aktif" {{ (request('status') == 'aktif' || (!request()->has('status'))) ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama/NIS..." 
                                value="{{ request('search') }}" style="width: 220px; min-width: 180px;">
                            
                            <button type="submit" class="btn btn-outline-success btn-sm d-flex align-items-center gap-2">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            
                            @if(request()->has('kelas') || request()->has('status') || request()->has('search'))
                                <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2">
                                    <i class="bi bi-x-circle"></i> Reset
                                </a>
                            @endif
                        </form>

                        <!-- Export Buttons -->
                        <div class="d-flex gap-3">
                            <a href="{{ route('siswa.export.pdf', ['kelas' => request('kelas'), 'status' => request('status'), 'search' => request('search')]) }}" 
                                class="btn btn-danger btn-sm d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                            </a>
                            <a href="{{ route('siswa.export.excel', ['kelas' => request('kelas'), 'status' => request('status'), 'search' => request('search')]) }}" 
                                class="btn btn-success btn-sm d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-excel-fill"></i> Excel
                            </a>
                            <a href="{{ route('siswa.create') }}" class="btn btn-success btn-sm d-flex align-items-center gap-2">
                                <i class="bi bi-plus-circle"></i> Tambah
                            </a>
                        </div>
                    </div>

                    <!-- Tabel Siswa -->
                    <div class="table-responsive">
                        @if($siswaList->isEmpty())
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i> Tidak ada data siswa.
                            </div>
                        @else
                            <table id="siswaTable" class="table table-striped table-bordered table-sm">
                                <thead class="bg-success text-white">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Nama Siswa</th>
                                        <th width="10%">NIS</th>
                                        <th width="10%">Kelas</th>
                                        <th width="25%">Tahun Ajaran</th>
                                        <th width="10%">Status</th>
                                        <th width="10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswaList as $index => $siswa)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $siswa->nama ?? '-' }}</td>
                                            <td>{{ $siswa->nis ?? '-' }}</td>
                                            <td>
                                                @if($siswa->kelas)
                                                    {{ $siswa->kelas->nama_kelas }}
                                                    {!! $siswa->kelas->getStatusBadgeHtml() !!}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($siswa->tahunAjaran)
                                                    {{ $siswa->tahunAjaran->nama_tahun_ajaran }}
                                                    @if($siswa->tahunAjaran->aktif)
                                                        <span class="badge bg-success">Aktif</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($siswa->status == 'aktif')
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Non-Aktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="javascript:void(0);" class="text-primary btn-view-siswa"
                                                        data-id="{{ $siswa->id_siswa }}" title="Lihat">
                                                        <i class="bi bi-eye-fill fs-5"></i>
                                                    </a>

                                                    <a href="{{ url('siswa/' . $siswa->id_siswa . '/edit') }}"
                                                        class="text-warning" title="Edit">
                                                        <i class="bi bi-pencil-square fs-5"></i>
                                                    </a>
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
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
                            <strong>NIS:</strong>
                            <p id="viewNis" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Jenis Kelamin:</strong>
                            <p id="viewGender" class="mb-2"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Lahir:</strong>
                            <p id="viewTanggalLahir" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Kelas:</strong>
                            <p id="viewKelas" class="mb-2"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Tahun Ajaran:</strong>
                            <p id="viewTahunAjaran" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p id="viewStatus" class="mb-2"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Orang Tua:</strong>
                            <p id="viewOrangTua" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Alamat:</strong>
                            <p id="viewAlamat" class="mb-0"></p>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle-fill me-2"></i> <strong>Informasi Status:</strong>
                        <p class="mb-0">Status siswa ditentukan oleh status kelas dan tahun ajaran. Perubahan status tahun ajaran akan otomatis mengubah status siswa.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="btn-edit-siswa" class="btn btn-warning">Edit</a>
                    <button type="button" id="btn-update-status" class="btn btn-primary">Update Status</button>
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
                { orderable: false, targets: [6] }, // Disable sorting on action column
                { searchable: false, targets: [0, 6] } // Disable searching on number and action columns
            ],
            "dom": '<"top"lf>rt<"bottom"ip><"clear">'
        });

        // Handle View Modal
        $('.btn-view-siswa').on('click', function() {
            const siswaData = $(this).data('siswa');
            
            // Update edit button href
            $('#btn-edit-siswa').attr('href', `{{ url('siswa') }}/${siswaData.id_siswa}/edit`);
            $('#btn-update-status').attr('data-id', siswaData.id_siswa);
            $('#btn-update-status').attr('data-name', siswaData.nama);
            
            // Set basic info
            $('#viewNama').text(siswaData.nama || '-');
            $('#viewNis').text(siswaData.nis || '-');
            $('#viewGender').text(siswaData.jenis_kelamin || '-');
            $('#viewTanggalLahir').text(siswaData.tanggal_lahir || '-');
            $('#viewAlamat').text(siswaData.alamat || '-');
            
            // Set kelas info
            if (siswaData.kelas) {
                let kelasText = `${siswaData.kelas.nama_kelas}`;
                if (siswaData.kelas.tahun_ajaran) {
                    kelasText += ` (${siswaData.kelas.tahun_ajaran.nama_tahun_ajaran})`;
                }
                $('#viewKelas').html(kelasText);
            } else {
                $('#viewKelas').text('-');
            }
            
            // Set tahun ajaran info
            if (siswaData.tahun_ajaran) {
                let tahunAjaranText = siswaData.tahun_ajaran.nama_tahun_ajaran;
                if (siswaData.tahun_ajaran.aktif) {
                    tahunAjaranText += ' <span class="badge bg-success">Aktif</span>';
                }
                $('#viewTahunAjaran').html(tahunAjaranText);
            } else {
                $('#viewTahunAjaran').text('-');
            }
            
            // Set status with badge
            if (siswaData.status === 'aktif') {
                $('#viewStatus').html('<span class="badge bg-success">Aktif</span>');
            } else {
                $('#viewStatus').html('<span class="badge bg-secondary">Non-Aktif</span>');
            }
            
            // Set orang tua info
            if (siswaData.orang_tua) {
                $('#viewOrangTua').text(siswaData.orang_tua.nama_lengkap || '-');
            } else {
                $('#viewOrangTua').text('-');
            }
        });
        
        // Handle Update Status
        $('.btn-update-status, #btn-update-status').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            Swal.fire({
                title: 'Konfirmasi Update Status',
                html: `<p>Apakah Anda yakin ingin memperbarui status siswa <strong>${name}</strong>?</p>
                      <div class="alert alert-info mt-3">
                        <strong>Informasi:</strong> Tindakan ini akan:
                        <ul class="mb-0 mt-1 text-left">
                          <li>Menyesuaikan status siswa berdasarkan status kelas dan tahun ajaran</li>
                          <li>Mengaktifkan siswa jika kelas berada pada tahun ajaran aktif</li>
                          <li>Menonaktifkan siswa jika kelas berada pada tahun ajaran nonaktif</li>
                        </ul>
                      </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/siswa/${id}/update-status`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Berhasil!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan!';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            
                            Swal.fire(
                                'Error!',
                                errorMessage,
                                'error'
                            );
                        }
                    });
                }
            });
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
                filterForm.find('select, input').addClass('w-100');
                filterForm.find('button[type="submit"]').addClass('w-100 mt-2');
            } else {
                filterForm.removeClass('flex-column align-items-start').addClass('align-items-center');
                filterControls.removeClass('w-100');
                filterForm.find('select, input').removeClass('w-100');
                filterForm.find('button[type="submit"]').removeClass('w-100 mt-2');
            }
        }
        
        // Initialize responsive layout
        adjustFilterFormLayout();
    });
</script>
@endsection
