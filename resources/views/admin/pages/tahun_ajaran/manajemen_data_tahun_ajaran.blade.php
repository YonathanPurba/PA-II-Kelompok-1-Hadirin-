@extends('layouts.admin-layout')

@section('title', 'Manajemen Data Tahun Ajaran')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <!-- Header Judul -->
            <header class="judul">
                <h1 class="mb-3">Manajemen Data Tahun Ajaran</h1>
                <p class="mb-2">Kelola data tahun ajaran dan status aktivasi</p>
            </header>

            <div class="data">
                <!-- Informasi Status -->
                <!-- <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill me-2"></i> <strong>Informasi Status Tahun Ajaran:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Menonaktifkan tahun ajaran lainnya</li>
                                <li>Mengaktifkan semua kelas pada tahun ajaran tersebut</li>
                                <li>Mengaktifkan semua siswa pada kelas-kelas tersebut</li>
                                <li>Menonaktifkan siswa pada kelas-kelas tahun ajaran lainnya</li>
                            </ul>
                        </div>
                    </div>
                </div> -->

                <!-- Tombol Tambah -->
                <div class="mb-4 text-end">
                    <a href="{{ route('tahun-ajaran.create') }}" class="btn btn-success px-4">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Tahun Ajaran
                    </a>
                </div>

                <!-- Tabel Tahun Ajaran -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm">
                        <thead class="bg-success text-white">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Tahun Ajaran</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th>Jumlah Kelas</th>
                                <th>Jumlah Siswa</th>
                                <th width="18%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tahunAjaran as $index => $ta)
                                <tr class="{{ $ta->aktif ? 'table-success' : '' }}">
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
                                    <td><span class="badge bg-info">{{ $ta->kelas_count }}</span></td>
                                    <td><span class="badge bg-info">{{ $ta->siswa_count }}</span></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <a href="{{ route('tahun-ajaran.edit', $ta->id_tahun_ajaran) }}"
                                                class="text-warning" title="Edit">
                                                <i class="bi bi-pencil-square fs-5"></i>
                                            </a>
                                            @if(!$ta->aktif)
                                                <a href="{{ route('tahun-ajaran.set-active', $ta->id_tahun_ajaran) }}"
                                                    class="text-success btn-activate" data-id="{{ $ta->id_tahun_ajaran }}"
                                                    data-name="{{ $ta->nama_tahun_ajaran }}" title="Aktifkan">
                                                    <i class="bi bi-check-circle-fill fs-5"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3">Tidak ada data tahun ajaran.</td>
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

        // Aktivasi Tahun Ajaran
        $('.btn-activate').on('click', function (e) {
            e.preventDefault();
            const name = $(this).data('name');
            const url = $(this).attr('href');

            Swal.fire({
                title: 'Konfirmasi Aktivasi',
                html: `<p>Apakah Anda yakin ingin mengaktifkan tahun ajaran <strong>${name}</strong>?</p>
                    <div class="alert alert-warning mt-3 text-start">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Perhatian:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Menonaktifkan tahun ajaran lainnya</li>
                            <li>Mengaktifkan semua kelas pada tahun ajaran ini</li>
                            <li>Mengaktifkan semua siswa pada kelas-kelas tersebut</li>
                            <li>Menonaktifkan siswa pada kelas-kelas tahun ajaran lainnya</li>
                        </ul>
                    </div>`,
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
