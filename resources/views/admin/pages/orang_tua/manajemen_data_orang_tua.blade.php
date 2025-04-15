@extends('layouts.admin-layout')

@section('title', 'Data Orang Tua')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Data Orang Tua</h1>
                    <p class="mb-2">Pilih kelas untuk memfilter data orang tua berdasarkan kelas anak mereka</p>
                </header>
                <div class="data">
                    <!-- Filter Kelas -->
                    <div class="mb-4">
                        <form method="GET" action="{{ route('orang-tua.index') }}" class="d-flex align-items-center gap-2">
                            <label for="kelas" class="me-2">Filter Kelas:</label>
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
                    </div>

                    <!-- Tabel Orang Tua -->
                    <div class="table-responsive">
                        <table id="orangtuaTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Orang Tua</th>
                                    <th>Nama Anak</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orangTuaList as $index => $orangTua)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $orangTua->nama_lengkap }}</td>
                                        <td>
                                            <ul class="mb-0 ps-3">
                                                @foreach ($orangTua->siswa as $anak)
                                                    <li>{{ $anak->nama }} ({{ $anak->kelas->nama_kelas }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-4">
                                                <a href="javascript:void(0);" class="text-primary btn-view-orangtua"
                                                    data-id="{{ $orangTua->id_orangtua }}"
                                                    data-nama="{{ $orangTua->nama_lengkap }}"
                                                    data-alamat="{{ $orangTua->alamat }}"
                                                    data-pekerjaan="{{ $orangTua->pekerjaan }}"
                                                    data-nomor="{{ $orangTua->user->nomor_telepon }}"
                                                    data-anak="{{ $orangTua->siswa->pluck('nama')->join(', ') }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalViewOrangtua"
                                                    title="Lihat">
                                                    <i class="bi bi-eye-fill fs-5"></i>
                                                </a>

                                                <a href="{{ route('orang-tua.edit', $orangTua->id_orangtua) }}"
                                                    class="text-warning" title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data orang tua.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- Modal View Orang Tua -->
    <div class="modal fade" id="modalViewOrangtua" tabindex="-1" aria-labelledby="modalViewOrangtuaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="modal-title" id="modalViewOrangtuaLabel">Detail Orang Tua</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nama Lengkap:</strong>
                            <p id="modal-nama" class="mb-2">-</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Pekerjaan:</strong>
                            <p id="modal-pekerjaan" class="mb-2">-</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nomor Telepon:</strong>
                            <p id="modal-nomor" class="mb-2">-</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Alamat:</strong>
                            <p id="modal-alamat" class="mb-2">-</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <strong>Anak:</strong>
                            <ul id="modal-anak" class="mb-0 ps-3">
                                <!-- Anak-anak akan diisi via JS -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection
