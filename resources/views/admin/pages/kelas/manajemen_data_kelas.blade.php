@extends('layouts.admin-layout')

@section('title', 'Manajemen Data Kelas')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Data Kelas</h1>
                    <p class="mb-2">Kelola data kelas dan hubungannya dengan tahun ajaran</p>
                </header>

                <div class="data">

                    <!-- Informasi Status -->
                    <!-- <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill me-2"></i> <strong>Informasi Status:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Status kelas akan otomatis berubah saat status tahun ajaran berubah</li>
                                    <li>Status siswa pada kelas akan mengikuti status kelas</li>
                                    <li>Mengubah tahun ajaran kelas akan mengubah status siswa pada kelas tersebut</li>
                                </ul>
                            </div>
                        </div>
                    </div> -->

                    <!-- Filter & Action Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <form action="{{ route('kelas.index') }}" method="GET" class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-2">
                                <label for="tingkat" class="me-2 mb-0">Tingkat:</label>
                                <select name="tingkat" id="tingkat" class="form-select">
                                    <option value="">Semua Tingkat</option>
                                    @foreach($tingkatList as $tingkat)
                                        <option value="{{ $tingkat }}" {{ request('tingkat') == $tingkat ? 'selected' : '' }}>{{ $tingkat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <label for="tahun_ajaran" class="me-2 mb-0">T/A:</label>
                                <select name="tahun_ajaran" id="tahun_ajaran" class="form-select">
                                    <option value="">Semua Tahun Ajaran</option>
                                    @foreach($tahunAjaranList as $ta)
                                        <option value="{{ $ta->id_tahun_ajaran }}" {{ request('tahun_ajaran') == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                                            {{ $ta->nama_tahun_ajaran }} {{ $ta->aktif ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <label for="search" class="me-2 mb-0">Cari:</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="Cari kelas..." value="{{ request('search') }}">
                            </div>

                            <button type="submit" class="btn btn-outline-success">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>

                             <a href="{{ route('kelas.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> Tambah
                        </a>

                            @if(request()->has('tingkat') || request()->has('tahun_ajaran') || request()->has('search'))
                                <a href="{{ route('kelas.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Reset
                                </a>
                                
                            @endif
                        </form>

                        
                    </div>

                    <!-- Tabel Kelas -->
                    <div class="table-responsive">
                        <table id="kelas-table" class="table table-striped table-bordered table-sm nowrap w-100">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Wali Kelas</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Status</th>
                                    <th>Jumlah Siswa</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelas as $index => $k)
                                    <tr class="{{ $k->isActive() ? 'table-success' : '' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $k->nama_kelas }}</td>
                                        <td>{{ $k->tingkat }}</td>
                                        <td>{{ $k->guru?->nama_lengkap ?? '-' }}</td>
                                        <td>
                                            @if($k->tahunAjaran)
                                                {{ $k->tahunAjaran->nama_tahun_ajaran }}
                                                @if($k->tahunAjaran->aktif)
                                                    <span class="badge bg-success">Aktif</span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{!! $k->getStatusBadgeHtml() !!}</td>
                                        <td><span class="badge bg-info">{{ $k->siswa->count() }}</span></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('kelas.edit', $k->id_kelas) }}" class="text-warning" title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-3">Tidak ada data kelas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
@endsection

@section('js')
<script>
    $(function () {
        // Warna khusus tahun ajaran aktif
        $('#tahun_ajaran option').each(function () {
            if ($(this).text().includes('(Aktif)')) {
                $(this).css({ 'background-color': '#d4edda', 'font-weight': 'bold' });
            }
        });

        // DataTables
        $('#kelas-table').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            buttons: [
                { extend: 'copy', className: 'btn btn-outline-secondary' },
                { extend: 'excel', className: 'btn btn-outline-success' },
                { extend: 'pdf', className: 'btn btn-outline-danger' },
                { extend: 'print', className: 'btn btn-outline-primary' }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ditemukan data yang cocok",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            }
        });

        // Hapus Kelas
        $('.btn-delete').on('click', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus kelas ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/kelas/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire('Berhasil!', response.message, 'success').then(() => location.reload());
                        },
                        error: function (xhr) {
                            const message = xhr.responseJSON?.message || 'Terjadi kesalahan!';
                            Swal.fire('Gagal!', message, 'error');
                        }
                    });
                }
            });
        });

        // Update Status
        $('.btn-update-status').on('click', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Konfirmasi Update Status',
                html: `<p>Update status semua siswa di kelas <strong>${name}</strong>?</p>
                    <div class="alert alert-info mt-2 text-start">
                        <ul class="mb-0">
                            <li>Status siswa mengikuti status tahun ajaran</li>
                            <li>Akan aktif jika tahun ajaran aktif, nonaktif jika tidak</li>
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
                        url: `/kelas/${id}/update-student-statuses`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire('Berhasil!', response.message, 'success').then(() => location.reload());
                        },
                        error: function (xhr) {
                            const message = xhr.responseJSON?.message || 'Terjadi kesalahan!';
                            Swal.fire('Gagal!', message, 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
