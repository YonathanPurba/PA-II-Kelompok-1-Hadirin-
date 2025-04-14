@extends('layouts.admin-layout')

@section('title', 'Data Orang Tua')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul mb-4">
                    <h1 class="mb-2">Data Orang Tua</h1>
                    <p class="text-muted">Pilih kelas untuk melihat data siswa dan orang tua</p>
                </header>
                <div class="data">
                    <div class="detail-kelas ">                         
                        <div class="daftar-kelas d-flex flex-wrap gap-4">
                            @forelse ($kelasList as $kelas)
                                <a href="{{ url('/orang-tua/kelas/' . $kelas->id_kelas) }}"
                                    class="text-decoration-none text-dark">
                                    <div class="card"
                                        style="width: 240px; height: auto; padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); background-color: #fff; display: flex; flex-direction: column; align-items: center; transition: transform 0.2s;">

                                        <!-- Icon -->
                                        <div style="font-size: 60px; color: #28a745; margin-bottom: 10px;">
                                            <i class="bi bi-door-open-fill"></i>
                                        </div>

                                        <!-- Info Kelas -->
                                        <div style="text-align: center;">
                                            <p><strong>{{ $kelas->nama_kelas ?? '-' }}</strong></p>
                                            <p>Jumlah Siswa: <strong>{{ $kelas->siswa_count ?? 0 }}</strong></p>
                                            <p>Wali: <strong>{{ $kelas->guru->nama_lengkap ?? '-' }}</strong></p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p style="padding: 10px;">Tidak ada data kelas yang tersedia.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
