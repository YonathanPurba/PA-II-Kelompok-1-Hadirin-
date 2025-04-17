@extends('layouts.admin-layout')

@section('title', 'Manajemen Kelas')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Kelas</h1>
                    <p class="mb-2">Staff dapat menambah, melihat, dan mengubah data kelas</p>
                </header>

                <div class="data">
                    <!-- Tabel Kelas -->
                    <div class="table-responsive">
                        <table id="kelasTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Guru Pengampu</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kelas as $index => $kelasItem)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $kelasItem->nama_kelas }}</td>
                                        <td>{{ $kelasItem->tingkat }}</td>
                                        <td>{{ $kelasItem->guru ? $kelasItem->guru->nama_lengkap : '-' }}</td>
                                        <!-- Menampilkan nama guru jika ada -->
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-4">
                                                <a href="javascript:void(0);" class="text-primary btn-view-kelas"
                                                    data-id="{{ $kelasItem->id_kelas }}"
                                                    data-nama="{{ $kelasItem->nama_kelas }}"
                                                    data-tingkat="{{ $kelasItem->tingkat }}"
                                                    data-guru="{{ $kelasItem->guru ? $kelasItem->guru->nama_lengkap : '-' }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalViewKelas" title="Lihat">
                                                    <i class="bi bi-eye-fill fs-5"></i>
                                                </a>
                                                <!-- Tombol Edit -->
                                                <a href="{{ route('kelas.edit', $kelasItem->id_kelas) }}"
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
                        <a href="{{ route('kelas.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Kelas
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Detail Kelas -->
    <div class="modal fade" id="modalViewKelas" tabindex="-1" aria-labelledby="modalViewKelasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalViewKelasLabel">Detail Kelas</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-4">
                        <dt class="col-sm-4">Nama Kelas</dt>
                        <dd class="col-sm-8" id="view-nama-kelas">-</dd>

                        <dt class="col-sm-4">Tingkat</dt>
                        <dd class="col-sm-8" id="view-tingkat">-</dd>

                        <dt class="col-sm-4">Guru Pengampu</dt>
                        <dd class="col-sm-8" id="view-guru">-</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

@endsection
