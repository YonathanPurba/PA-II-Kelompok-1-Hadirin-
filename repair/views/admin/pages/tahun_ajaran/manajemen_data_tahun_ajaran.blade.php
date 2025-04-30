@extends('layouts.admin-layout')

@section('title', 'Manajemen Tahun Ajaran')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Tahun Ajaran</h1>
                    <p class="mb-2">Staff dapat menambah, melihat, dan mengubah data tahun ajaran</p>
                </header>

                <div class="data">
                    <!-- Tabel Tahun Ajaran -->
                    <div class="table-responsive">
                        <table id="tahunAjaranTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Tahun Ajaran</th>                                  
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tahunAjaran as $index => $tahun)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $tahun->nama_tahun_ajaran }}</td>                                            
                                        <td>
                                            @if ($tahun->aktif)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-4">
                                                <a href="javascript:void(0);" class="text-primary btn-view-tahun"
                                                    data-id="{{ $tahun->id_tahun_ajaran }}"
                                                    data-nama="{{ $tahun->nama_tahun_ajaran }}"
                                                    data-mulai="{{ \Carbon\Carbon::parse($tahun->tanggal_mulai)->format('d-m-Y') }}"
                                                    data-selesai="{{ \Carbon\Carbon::parse($tahun->tanggal_selesai)->format('d-m-Y') }}"
                                                    data-status="{{ $tahun->aktif ? 'Aktif' : 'Tidak Aktif' }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalViewTahun" title="Lihat">
                                                    <i class="bi bi-eye-fill fs-5"></i>
                                                </a>


                                                <a href="{{ route('tahun-ajaran.edit', $tahun->id_tahun_ajaran) }}"
                                                    class="text-warning" title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol Tambah -->
                    <div class="mt-4 text-end">
                        <a href="{{ route('tahun-ajaran.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Tahun Ajaran
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal View Tahun Ajaran -->
    <div class="modal fade" id="modalViewTahun" tabindex="-1" aria-labelledby="modalViewTahunLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalViewTahunLabel">Detail Tahun Ajaran</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-4">
                        <dt class="col-sm-4">Nama Tahun Ajaran</dt>
                        <dd class="col-sm-8" id="view-nama-tahun">-</dd>

                        <dt class="col-sm-4">Tanggal Mulai</dt>
                        <dd class="col-sm-8" id="view-tanggal-mulai">-</dd>

                        <dt class="col-sm-4">Tanggal Selesai</dt>
                        <dd class="col-sm-8" id="view-tanggal-selesai">-</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8" id="view-status">-</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

@endsection
