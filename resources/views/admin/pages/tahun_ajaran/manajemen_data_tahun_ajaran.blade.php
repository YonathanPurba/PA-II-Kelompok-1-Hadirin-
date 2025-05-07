@extends('layouts.admin-layout')

@section('title', 'Manajemen Data Tahun Ajaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Tahun Ajaran</h3>
                    <div class="card-tools">
                        <a href="{{ route('tahun-ajaran.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Tahun Ajaran
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Tahun ajaran aktif akan mempengaruhi status kelas, siswa, dan orang tua. Hanya satu tahun ajaran yang dapat aktif pada satu waktu.
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Tahun Ajaran</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Jumlah Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tahunAjaran as $index => $ta)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $ta->nama_tahun_ajaran }}</td>
                                    <td>{{ $ta->tanggal_mulai->format('d-m-Y') }}</td>
                                    <td>{{ $ta->tanggal_selesai->format('d-m-Y') }}</td>
                                    <td>
                                        @if($ta->aktif)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $ta->kelas_count }}</td>
                                    <td>{{ $ta->siswa_count }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('tahun-ajaran.edit', $ta->id_tahun_ajaran) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$ta->aktif)
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $ta->id_tahun_ajaran }}" data-name="{{ $ta->nama_tahun_ajaran }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <a href="{{ route('tahun-ajaran.set-active', $ta->id_tahun_ajaran) }}" class="btn btn-sm btn-success btn-activate" data-id="{{ $ta->id_tahun_ajaran }}" data-name="{{ $ta->nama_tahun_ajaran }}">
                                                    <i class="fas fa-check"></i> Aktifkan
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data tahun ajaran</td>
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
        // Delete academic year
        $('.btn-delete').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus tahun ajaran ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/tahun-ajaran/${id}`,
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
        
        // Activate academic year
        $('.btn-activate').on('click', function(e) {
            e.preventDefault();
            
            const id = $(this).data('id');
            const name = $(this).data('name');
            const url = $(this).attr('href');
            
            Swal.fire({
                title: 'Konfirmasi Aktivasi',
                text: `Apakah Anda yakin ingin mengaktifkan tahun ajaran ${name}? Ini akan menonaktifkan tahun ajaran lain dan mempengaruhi status kelas, siswa, dan orang tua.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Aktifkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
@endsection
