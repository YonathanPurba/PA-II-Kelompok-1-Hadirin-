@extends('layouts.admin-layout')

@section('title', 'Rekapitulasi Absensi')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <header class="judul">
                <h1 class="mb-3">Rekapitulasi Absensi</h1>
                <p class="mb-2">Lihat dan unduh rekapitulasi kehadiran siswa berdasarkan kelas</p>
            </header>
            <div class="data">
                <div class="mb-4">
                    <h2 class="text-center mb-4">Pilih Kelas</h2>
                    <div class="daftar-kelas d-flex flex-wrap justify-content-center gap-4">
                        @forelse ($kelasList as $kelas)
                            <a href="{{ url('rekapitulasi/kelas/' . $kelas->id_kelas) }}" class="text-decoration-none">
                                <div class="card shadow-sm" style="width: 180px; height: 120px;">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <div class="fs-1 text-success mb-2">
                                            <i class="bi bi-door-open-fill"></i>
                                        </div>
                                        <h3 class="card-title fs-4 text-center mb-0">{{ $kelas->nama_kelas }}</h3>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>Belum ada data kelas
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
