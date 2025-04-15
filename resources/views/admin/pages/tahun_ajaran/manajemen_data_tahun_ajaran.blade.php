@extends('layouts.admin-layout')

@section('title', 'Manajemen Tahun Ajaran')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Tahun Ajaran</h1>
                    <p class="mb-2">Kelola data tahun ajaran di sini. Anda dapat menambah, mengedit, atau melihat detail tahun ajaran.</p>
                </header>

                <!-- Tabel Tahun Ajaran -->
                <div class="data">
                    <a href="{{ route('tahun-ajaran.create') }}" class="btn btn-primary mb-3">Tambah Tahun Ajaran</a>
                    
                    <!-- Tabel Tahun Ajaran -->
                    <div class="table-responsive">
                        <table id="tahunAjaranTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Tahun Ajaran</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tahunAjaran as $tahun)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tahun->nama_tahun_ajaran }}</td>
                                        <td>{{ \Carbon\Carbon::parse($tahun->tanggal_mulai)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($tahun->tanggal_selesai)->format('d-m-Y') }}</td>
                                        <td>
                                            @if ($tahun->aktif)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-4">
                                                <a href="{{ route('tahun-ajaran.show', $tahun->id_tahun_ajaran) }}" class="text-primary" title="Lihat">
                                                    <i class="bi bi-eye-fill fs-5"></i>
                                                </a>
                                                <a href="{{ route('tahun-ajaran.edit', $tahun->id_tahun_ajaran) }}" class="text-warning" title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>
                                                <form action="{{ route('tahun-ajaran.destroy', $tahun->id_tahun_ajaran) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
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

@endsection
