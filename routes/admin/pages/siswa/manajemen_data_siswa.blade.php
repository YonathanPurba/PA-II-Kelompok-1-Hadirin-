@extends('layouts.admin-layout')

@section('title', 'Data Siswa')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Data Siswa</h1>
                    <p class="mb-2">Pilih kelas untuk memfilter data siswa berdasarkan kelas</p>
                </header>

                <div class="data">

                    <!-- Filter & Export Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <form method="GET" action="{{ route('siswa.index') }}" class="d-flex align-items-center gap-2">
                            <label for="kelas" class="me-2 mb-0">Filter Kelas:</label>
                            <select name="kelas" id="kelas" class="form-select w-auto" onchange="this.form.submit()">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id_kelas }}"
                                        {{ request('kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <div>
                            <a href="{{ route('siswa.export.pdf', ['kelas' => request('kelas')]) }}"
                                class="btn btn-danger me-2">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
                            </a>
                            <a href="{{ route('siswa.export.excel', ['kelas' => request('kelas')]) }}"
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
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Jenis Kelamin</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswaList as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $siswa->nama ?? '-' }}</td>
                                        <td>{{ $siswa->jenis_kelamin ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-4">
                                                {{-- Tombol View --}}
                                                <a href="javascript:void(0);" class="text-primary btn-view-siswa"
                                                    data-siswa='@json($siswa)' data-bs-toggle="modal"
                                                    data-bs-target="#modalViewSiswa" title="Lihat">
                                                    <i class="bi bi-eye-fill fs-5"></i>
                                                </a>

                                                <!-- Edit Button -->
                                                <a href="{{ url('siswa/' . $siswa->id_siswa . '/edit') }}"
                                                    class="text-warning" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data siswa.</td>
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
                    <div class="row">
                        <div class="col-md-6">
                            <strong>NISN:</strong>
                            <p id="viewNisn" class="mb-2"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Kelas:</strong>
                            <p id="viewKelas" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Alamat:</strong>
                            <p id="viewAlamat" class="mb-0"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
