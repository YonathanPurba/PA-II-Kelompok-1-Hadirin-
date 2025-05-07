@extends('layouts.admin-layout')

@section('title', 'Manajemen Data Kelas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Kelas</h3>
                    <div class="card-tools">
                        <a href="{{ route('kelas.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Kelas
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Status kelas ditentukan oleh tahun ajaran. Kelas dengan tahun ajaran aktif akan memiliki status "Aktif".
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('kelas.index') }}" method="GET" class="form-inline">
                                <div class="form-group mr-2">
                                    <select name="tingkat" class="form-control">
                                        <option value="">-- Pilih Tingkat --</option>
                                        @foreach($tingkatList as $tingkat)
                                            <option value="{{ $tingkat }}" {{ request('tingkat') == $tingkat ? 'selected' : '' }}>{{ $tingkat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <select name="tahun_ajaran" class="form-control">
                                        <option value="">-- Pilih Tahun Ajaran --</option>
                                        @foreach($tahunAjaranList as $ta)
                                            <option value="{{ $ta->id_tahun_ajaran }}" {{ request('tahun_ajaran') == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                                                {{ $ta->nama_tahun_ajaran }} {{ $ta->aktif ? '(Aktif)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <input type="text" name="search" class="form-control" placeholder="Cari kelas..." value="{{ request('search') }}">
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Reset</a>
                            </form>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Wali Kelas</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Status</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelas as $index => $k)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $k->nama_kelas }}</td>
                                    <td>{{ $k->tingkat }}</td>
                                    <td>{{ $k->guru ? $k->guru->nama_lengkap : '-' }}</td>
                                    <td>{{ $k->tahunAjaran ? $k->tahunAjaran->nama_tahun_ajaran : '-' }}</td>
                                    <td>{!! $k->getStatusBadgeHtml() !!}</td>
                                    <td>{{ $k->siswa->count() }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('kelas.edit', $k->id_kelas) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $k->id_kelas }}" data-name="{{ $k->nama_kelas }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info btn-update-status" data-id="{{ $k->id_kelas }}" data-name="{{ $k->nama_kelas }}">
                                                <i class="fas fa-sync"></i> Update Status
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data kelas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function() {
        // Delete class
        $('.btn-delete').on('click', function() {
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
        
        // Update student statuses in class
        $('.btn-update-status').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            Swal.fire({
                title: 'Konfirmasi Update Status',
                text: `Apakah Anda yakin ingin memperbarui status semua siswa di kelas ${name}?`,
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
    });
</script>
@endsection
