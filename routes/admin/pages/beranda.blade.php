@extends('layouts.admin-layout')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Judul Header -->
                <header class="judul">
                    <h1 class="mb-3">Beranda Admin</h1>
                    <p class="mb-2">Selamat datang di halaman utama sistem informasi absensi</p>
                </header>

                <div class="data">
                    <div class="statistik-sekolah">
                        <!-- Header Section -->
                        <div class="header-data d-flex justify-content-between align-items-center mb-4">
                            <h2>Statistik Sekolah</h2>
                        </div>
                        <!-- Kartu Statistik -->
                        <div class="daftar-kelas d-flex flex-wrap gap-4">
                            <!-- Jumlah Guru -->
                            <div class="card flex-fill p-4 rounded shadow" style="background-color: #fff;">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="font-size: 40px; color: #17a2b8;">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fs-6">Total Guru</p>
                                        <h3 class="mb-0">{{ $jumlahGuru }}</h3>
                                    </div>
                                </div>
                                <!-- View All -->
                                <div class="d-flex justify-content-end mt-3 w-100">
                                    <a href="{{ url('/guru') }}" class="fs-7 text-decoration-none text-primary">
                                        View All <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Jumlah Siswa -->
                            <div class="card flex-fill p-4 rounded shadow" style="background-color: #fff;">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="font-size: 40px; color: #28a745;">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fs-6">Total Siswa</p>
                                        <h3 class="mb-0">{{ $jumlahSiswa }}</h3>
                                    </div>
                                </div>
                                <!-- Tombol View All di kanan -->
                                <div class="d-flex justify-content-end mt-3 w-100">
                                    <a href="{{ url('/siswa ') }}" class="fs-7 text-decoration-none text-success">
                                        View All <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Jumlah Kelas -->
                            <div class="card flex-fill p-4 rounded shadow" style="background-color: #fff;">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="font-size: 40px; color: #ffc107;">
                                        <i class="bi bi-building-fill"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fs-6">Total Kelas</p>
                                        <h3 class="mb-0">{{ $jumlahKelas }}</h3>
                                    </div>
                                </div>
                                <!-- View All -->
                                <div class="d-flex justify-content-end mt-3 w-100">
                                    <a href="{{ url('/kelas') }}" class="fs-7 text-decoration-none text-warning">
                                        View All <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid mt-5">
                        <!-- Header Section -->
                        <div class="header-data d-flex justify-content-between align-items-center mb-5">
                            <h2>Statistik Absensi</h2>
                        </div>

                        <!-- Kontainer Utama -->
                        <div class="d-flex flex-wrap gap-5 justify-content-center">
                            <!-- Grafik + Total + Penjelasan dalam satu kolom vertikal -->
                            <div class="grafik-total-data bg-white p-4 rounded shadow" style="flex: 1 1 600px;">
                                <!-- Grafik Absensi -->
                                <div id="grafik-container" style="height: 400px;">
                                    <canvas id="barChartAbsensiBulan" data-labels='@json($absensiPerHari->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->translatedFormat('d M')))'
                                        data-hadir='@json($absensiPerHari->pluck('hadir'))' data-alpa='@json($absensiPerHari->pluck('alpa'))'
                                        data-sakit='@json($absensiPerHari->pluck('sakit'))' data-izin='@json($absensiPerHari->pluck('izin'))'>
                                    </canvas>
                                </div>


                                <div class="keterangan d-flex gap-5 mt-4">
                                    <!-- Total Absensi -->
                                    <div>
                                        <h4 class="mb-3 text-dark">Total Absensi</h4>
                                        <ul class="list-unstyled fs-5 text-dark">
                                            <li><strong class="text-primary">Hadir:</strong> {{ $totalHadir }}</li>
                                            <li><strong class="text-danger">Alpa:</strong> {{ $totalAlpa }}</li>
                                            <li><strong class="text-warning">Sakit:</strong> {{ $totalSakit }}</li>
                                            <li><strong class="text-success">Izin:</strong> {{ $totalIzin }}</li>
                                        </ul>
                                    </div>

                                    <!-- Total Minggu Ini -->
                                    <div>
                                        <h4 class="mb-3 text-dark">Total Minggu Ini</h4>
                                        <ul class="list-unstyled fs-5 text-dark">
                                            <li><strong class="text-primary">Hadir:</strong> {{ $totalMingguIni->hadir }}
                                            </li>
                                            <li><strong class="text-danger">Alpa:</strong> {{ $totalMingguIni->alpa }}</li>
                                            <li><strong class="text-warning">Sakit:</strong> {{ $totalMingguIni->sakit }}
                                            </li>
                                            <li><strong class="text-success">Izin:</strong> {{ $totalMingguIni->izin }}
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Absensi Berdasarkan Kelas -->                                    
                                    <div>
                                        <h4 class="mb-3 text-dark">Absensi Minggu Ini per Kelas</h4>
                                        <table class="table table-bordered table-sm">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="p-2 text-start">Kategori</th>
                                                    @foreach ($absensiPerKelasMingguIni as $kelas)
                                                        <th class="p-2 text-center">{{ $kelas->nama_kelas }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th class="p-2 text-start text-primary">Hadir</th>
                                                    @foreach ($absensiPerKelasMingguIni as $kelas)
                                                        <td class="p-2 text-center">{{ $kelas->hadir }}</td>
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    <th class="p-2 text-start text-danger">Alpa</th>
                                                    @foreach ($absensiPerKelasMingguIni as $kelas)
                                                        <td class="p-2 text-center">{{ $kelas->alpa }}</td>
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    <th class="p-2 text-start text-warning">Sakit</th>
                                                    @foreach ($absensiPerKelasMingguIni as $kelas)
                                                        <td class="p-2 text-center">{{ $kelas->sakit }}</td>
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    <th class="p-2 text-start text-success">Izin</th>
                                                    @foreach ($absensiPerKelasMingguIni as $kelas)
                                                        <td class="p-2 text-center">{{ $kelas->izin }}</td>
                                                    @endforeach
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <!-- Penjelasan Grafik -->
                                <div class="mt-4">
                                    <h4 class="mb-3 text-dark">Penjelasan Grafik Absensi</h4>
                                    <ul class="fs-5 text-dark">
                                        <li><strong class="text-success">Hadir:</strong> Total kehadiran siswa tiap bulan
                                        </li>
                                        <li><strong class="text-danger">Alpa:</strong> Ketidakhadiran tanpa keterangan</li>
                                        <li><strong class="text-warning">Sakit:</strong> Ketidakhadiran karena sakit</li>
                                        <li><strong class="text-primary">Izin:</strong> Ketidakhadiran dengan izin resmi
                                        </li>
                                    </ul>
                                    <p class="fs-6 text-dark mt-4">
                                        Data ini membantu memantau konsistensi kehadiran siswa dan mendeteksi tren yang
                                        perlu perhatian khusus.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid mt-5">
                        <!-- Informasi Guru -->
                        <div class="header-data d-flex justify-content-between align-items-center mb-5">
                            <h2>Informasi Guru</h2>
                        </div>

                        <div class="table-responsive">
                            <table id="guruTable" class="table table-striped table-bordered table-sm">
                                <thead class="bg-success text-white">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Guru</th>
                                        <th>Mata Pelajaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guru as $index => $g)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $g->nama_lengkap }}</td>
                                            <td>
                                                @forelse ($g->mataPelajaran as $mp)
                                                    {{ $mp->nama }}<br>
                                                @empty
                                                    <span>-</span>
                                                @endforelse
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="container-fluid mt-5">
                        <!-- Detail Kelas -->
                        <div class="header-data d-flex justify-content-between align-items-center mb-5">
                            <h2>Informasi Kelas</h2>
                        </div>
                        <div class="daftar-kelas"
                            style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 20px;">
                            @forelse ($detailKelas as $kelases)
                                <div class="card"
                                    style="width: 240px; height: auto; padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); background-color: #fff; display: flex; flex-direction: column; align-items: center;">
                                    <!-- Icon -->
                                    <div style="font-size: 60px; color: #28a745; margin-bottom: 10px;">
                                        <i class="bi bi-door-open-fill"></i>
                                    </div>

                                    <div style="text-align: center;">
                                        <p><strong>{{ $kelases->nama_kelas ?? '-' }}</strong></p>
                                        <p>Wali: <strong>{{ $kelases->guru->nama_lengkap ?? '-' }}</strong></p>
                                    </div>
                                </div>
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
