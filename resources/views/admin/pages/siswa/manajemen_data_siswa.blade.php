@extends('layouts.admin-layout')

@section('title', 'Data Siswa')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Data Siswa</h1>
                    <p class="mb-4">Pilih kelas untuk memfilter data siswa berdasarkan kelas</p>
                </header>

                <div class="data">
                    <!-- Filter Kelas -->
                    <div class="mb-4">
                        <form method="GET" action="{{ route('siswa.index') }}" class="d-flex align-items-center gap-2">
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

                    <!-- Tabel Siswa -->
                    <div class="table-responsive">
                        <table id="siswaTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Kelas</th>
                                    <th>Tempat Tanggal Lahir</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswaList as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $siswa->nama }}</td>
                                        <td>{{ $siswa->jenis_kelamin }}</td>
                                        <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                                        <td>{{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-4">
                                                <a href="{{ route('siswa.edit', $siswa->id_siswa) }}" class="text-warning" title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>

                                                <form action="{{ route('siswa.destroy', $siswa->id_siswa) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn p-0 text-danger" title="Hapus">
                                                        <i class="bi bi-trash3-fill fs-5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data siswa.</td>
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
@endsection
