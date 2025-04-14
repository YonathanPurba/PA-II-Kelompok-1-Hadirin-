@extends('layouts.admin-layout')

@section('title', 'Manajemen Data Guru')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Data Guru</h1>
                    <p class="mb-4">Staff dapat menambah, melihat, dan mengubah data guru</p>
                </header>            

                <!-- Data Guru -->
                <div class="data">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="{{ url('guru/create') }}" class="btn btn-success">
                            Tambah Guru
                        </a>
                    </div>

                    <!-- Tabel Data Guru -->
                    <div class="table-responsive">
                        <table id="guruTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>NIP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gurus as $index => $guru)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $guru->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $guru->nip ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-4">
                                                <a href="javascript:void(0);" class="text-primary btn-view-guru"
                                                    data-id="{{ $guru->id_guru }}" data-bs-toggle="modal"
                                                    data-bs-target="#modalViewGuru" title="Lihat">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>

                                                <a href="{{ url('guru/' . $guru->id_guru . '/edit') }}" class="text-warning"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
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

    <!-- Modal View Guru -->
    <div class="modal fade" id="modalViewGuru" tabindex="-1" aria-labelledby="modalViewGuruLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalViewGuruLabel">Detail Guru</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-4">
                        <dt class="col-sm-4">Nama Lengkap</dt>
                        <dd class="col-sm-8" id="view-nama-lengkap">-</dd>

                        <dt class="col-sm-4">NIP</dt>
                        <dd class="col-sm-8" id="view-nip">-</dd>

                        <dt class="col-sm-4">Nomor Telepon</dt>
                        <dd class="col-sm-8" id="view-telepon">-</dd>

                        <dt class="col-sm-4">Mata Pelajaran</dt>
                        <dd class="col-sm-8" id="view-mapel">-</dd>

                        <dt class="col-sm-4">Terakhir Login</dt>
                        <dd class="col-sm-8" id="view-terakhir-login">-</dd>

                        <dt class="col-sm-4">Jumlah Jadwal Mengajar</dt>
                        <dd class="col-sm-8" id="view-jadwal">-</dd>
                    </dl>

                    <hr>

                    <h5 class="mb-3">Jadwal Mengajar</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Hari</th>
                                    <th>Waktu</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                </tr>
                            </thead>
                            <tbody id="table-jadwal-body">
                                <tr>
                                    <td colspan="5" class="text-center">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
