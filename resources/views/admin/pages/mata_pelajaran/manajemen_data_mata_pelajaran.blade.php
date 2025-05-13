@extends('layouts.admin-layout')

@section('title', 'Manajemen Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <header class="judul">
                    <h1 class="mb-3">Manajemen Mata Pelajaran</h1>
                    <p class="mb-2">Staff dapat menambah, melihat, dan mengubah data mata pelajaran</p>
                </header>

                <div class="data">
                    <div class="mb-4 text-end">
                        <a href="{{ route('mata-pelajaran.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Mata Pelajaran
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table id="mataPelajaranTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mataPelajaran as $index => $mapel)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $mapel->nama }}</td>
                                        <td>{{ $mapel->kode }}</td>
                                        <td>{{ $mapel->deskripsi ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-4">
                                                <a href="javascript:void(0);" class="text-primary btn-view-mapel"
                                                    data-id="{{ $mapel->id_mata_pelajaran }}"
                                                    data-nama="{{ $mapel->nama }}" data-kode="{{ $mapel->kode }}"
                                                    data-deskripsi="{{ $mapel->deskripsi ?? '-' }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalViewMapel"
                                                    title="Lihat">
                                                    <i class="bi bi-eye-fill fs-5"></i>
                                                </a>
                                                <a href="{{ route('mata-pelajaran.edit', $mapel->id_mata_pelajaran) }}"
                                                    class="text-warning" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Detail Mata Pelajaran -->
    <div class="modal fade" id="modalViewMapel" tabindex="-1" aria-labelledby="modalViewMapelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalViewMapelLabel">Detail Mata Pelajaran</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-4">
                        <dt class="col-sm-4">Nama</dt>
                        <dd class="col-sm-8" id="view-nama">-</dd>

                        <dt class="col-sm-4">Kode</dt>
                        <dd class="col-sm-8" id="view-kode">-</dd>

                        <dt class="col-sm-4">Deskripsi</dt>
                        <dd class="col-sm-8" id="view-deskripsi">-</dd>

                        <dt class="col-sm-4">Jumlah Guru Pengampu</dt>
                        <dd class="col-sm-8" id="view-jumlah-guru">-</dd>
                    </dl>

                    <div class="text-end mb-2">
                        <button class="btn btn-outline-success btn-sm" type="button" data-bs-toggle="collapse"
                            data-bs-target="#listGuruPengampu" aria-expanded="false" aria-controls="listGuruPengampu"
                            id="toggleGuruBtn">
                            Lihat Semua Guru Pengampu
                        </button>
                    </div>

                    <div class="collapse" id="listGuruPengampu">
                        <ul class="list-group" id="guruPengampuList">
                            <!-- Akan diisi via JS -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
            $('.btn-view-mapel').on('click', function () {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const kode = $(this).data('kode');
                const deskripsi = $(this).data('deskripsi');

                $('#view-nama').text(nama);
                $('#view-kode').text(kode);
                $('#view-deskripsi').text(deskripsi);
                $('#view-jumlah-guru').text('Memuat...');

                $('#guruPengampuList').empty();

                $.ajax({
                    url: `/mata-pelajaran/${id}/guru-pengampu`,
                    method: 'GET',
                    success: function (response) {
                        if (response.length > 0) {
                            $('#view-jumlah-guru').text(response.length + ' guru');
                            response.forEach(guru => {
                                $('#guruPengampuList').append(`
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        ${guru.nama}
                                        <span class="badge bg-primary">${guru.nip}</span>
                                    </li>
                                `);
                            });
                        } else {
                            $('#view-jumlah-guru').text('0 guru');
                            $('#guruPengampuList').append('<li class="list-group-item">Belum ada guru pengampu.</li>');
                        }
                    },
                    error: function () {
                        $('#view-jumlah-guru').text('Gagal memuat data');
                        $('#guruPengampuList').append('<li class="list-group-item text-danger">Terjadi kesalahan saat mengambil data.</li>');
                    }
                });
            });
        });
    </script>
@endpush
