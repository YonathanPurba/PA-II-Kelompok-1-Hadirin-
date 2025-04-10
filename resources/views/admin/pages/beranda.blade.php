@extends('layouts.admin-layout')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container">
        <main class="main-content">
            <div class="isi">
                <!-- Judul Header -->
                <header class="judul">
                    <h1>Dashboard Admin</h1>
                    <p>Selamat datang di halaman utama sistem informasi absensi</p>
                </header>

                <div class="data">
                    <div class="statistik-sekolah">
                        <!-- Header Section -->
                        <div class="header-data"
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px">
                            <h2>Statistik Sekolah</h2>
                        </div>
                        <!-- Kartu Statistik -->
                        <div class="daftar-kelas" style="display: flex; flex-wrap: wrap; gap: 20px;">

                            <!-- Jumlah Guru -->
                            <div class="card"
                                style="flex: 1 1 220px; padding: 20px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); background-color: #fff; display: flex; flex-direction: column; justify-content: space-between; gap: 15px;">

                                <!-- Bagian ikon dan jumlah -->
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="font-size: 40px; color: #17a2b8;">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </div>
                                    <div>
                                        <p style="margin: 0; font-size: 14px;">Total Guru</p>
                                        <h3 style="margin: 0;">{{ $jumlahGuru }}</h3>
                                    </div>
                                </div>

                                <!-- View All -->
                                <div class="button-right" style="display: flex; justify-content: flex-end; width:100%">
                                    <a href="" style="font-size: 12px; text-decoration: none; color: #17a2b8;">
                                        View All <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Jumlah Siswa -->
                            <div class="card"
                                style="flex: 1 1 220px; padding: 20px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); background-color: #fff; display: flex; flex-direction: column; justify-content: center; gap: 15px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="font-size: 40px; color: #28a745;">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div>
                                        <p style="margin: 0; font-size: 14px;">Total Siswa</p>
                                        <h3 style="margin: 0;">{{ $jumlahSiswa }}</h3>
                                    </div>
                                </div>

                                <!-- Tombol View All di kanan -->
                                <div class="button-right" style="display: flex; justify-content: flex-end; width:100%">
                                    <a href="" style="font-size: 12px; text-decoration: none; color: #28a745;">
                                        View All <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Jumlah Kelas -->
                            <div class="card"
                                style="flex: 1 1 220px; padding: 20px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); background-color: #fff; display: flex; flex-direction: column; justify-content: space-between; gap: 15px;">

                                <!-- Ikon dan Jumlah -->
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="font-size: 40px; color: #ffc107;">
                                        <i class="bi bi-building-fill"></i>
                                    </div>
                                    <div>
                                        <p style="margin: 0; font-size: 14px;">Total Kelas</p>
                                        <h3 style="margin: 0;">{{ $jumlahKelas }}</h3>
                                    </div>
                                </div>

                                <!-- View All -->
                                <div class="button-right" style="display: flex; justify-content: flex-end; width:100%">
                                    <a href="" style="font-size: 12px; text-decoration: none; color: #ffc107;">
                                        View All <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="statistik-absensi" style="margin-top: 30px;">
                        <!-- Header Section -->
                        <div class="header-data"
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                            <h2>Statistik Absensi</h2>
                        </div>

                        <!-- Kontainer Utama -->
                        <div style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: center;">

                            <!-- Grafik + Total + Penjelasan dalam satu kolom vertikal -->
                            <div class="grafik-total-data"
                                style="flex: 1 1 600px; display: flex; flex-direction: column; gap: 30px; background: #fff; 
                                       padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">

                                <!-- Grafik Absensi -->
                                <div>
                                    <canvas id="barChartAbsensiBulan" height="100"></canvas>
                                </div>

                                <div class="keterangan" style="display: flex; gap: 40px;">
                                    <!-- Total Absensi -->
                                    <div>
                                        <h4 style="margin-bottom: 20px; color: #333;">Total Absensi</h4>
                                        <ul style="padding-left: 0; list-style: none; font-size: 16px; color: #222;">
                                            <li><strong style="color: #007bff;">Hadir:</strong> {{ $totalHadir }}</li>
                                            <li><strong style="color: #dc3545;">Alpa:</strong> {{ $totalAlpa }}</li>
                                            <li><strong style="color: #fd7e14;">Sakit:</strong> {{ $totalSakit }}</li>
                                            <li><strong style="color: #20c997;">Izin:</strong> {{ $totalIzin }}</li>
                                        </ul>
                                    </div>

                                    <!-- Total Minggu Ini -->
                                    <div>
                                        <h4 style="margin-bottom: 20px; color: #333;">Total Minggu Ini</h4>
                                        <ul style="padding-left: 0; list-style: none; font-size: 16px; color: #222;">
                                            <li><strong style="color: #007bff;">Hadir:</strong> {{ $totalMingguIni->hadir }}
                                            </li>
                                            <li><strong style="color: #dc3545;">Alpa:</strong> {{ $totalMingguIni->alpa }}
                                            </li>
                                            <li><strong style="color: #fd7e14;">Sakit:</strong> {{ $totalMingguIni->sakit }}
                                            </li>
                                            <li><strong style="color: #20c997;">Izin:</strong> {{ $totalMingguIni->izin }}
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Absensi Berdasarkan Kelas -->
                                    <div style="">
                                        <h4 style="margin-bottom: 20px; color: #333;">Absensi Minggu Ini per Kelas</h4>
                                        <table style="width: 100%; border-collapse: collapse; font-size: 15px;">
                                            <thead>
                                                <tr style="background-color: #f0f0f0;">
                                                    <th style="padding: 8px; text-align: left;">Kelas</th>
                                                    <th style="padding: 8px; text-align: left; color: #007bff;">Hadir</th>
                                                    <th style="padding: 8px; text-align: left; color: #dc3545;">Alpa</th>
                                                    <th style="padding: 8px; text-align: left; color: #fd7e14;">Sakit</th>
                                                    <th style="padding: 8px; text-align: left; color: #20c997;">Izin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($absensiPerKelasMingguIni as $kelas)
                                                    <tr>
                                                        <td style="padding: 8px;">{{ $kelas->nama_kelas }}</td>
                                                        <td style="padding: 8px;">{{ $kelas->hadir }}</td>
                                                        <td style="padding: 8px;">{{ $kelas->alpa }}</td>
                                                        <td style="padding: 8px;">{{ $kelas->sakit }}</td>
                                                        <td style="padding: 8px;">{{ $kelas->izin }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <!-- Penjelasan Grafik -->
                                <div>
                                    <h4 style="margin-bottom: 20px; color: #333;">📊 Penjelasan Grafik Absensi</h4>
                                    <ul style="padding-left: 20px; color: #555; font-size: 15px;">
                                        <li><strong style="color: #28a745;">Hadir:</strong> Total kehadiran siswa tiap bulan
                                        </li>
                                        <li><strong style="color: #e83e8c;">Alpa:</strong> Ketidakhadiran tanpa keterangan
                                        </li>
                                        <li><strong style="color: #ffc107;">Sakit:</strong> Ketidakhadiran karena sakit</li>
                                        <li><strong style="color: #17a2b8;">Izin:</strong> Ketidakhadiran dengan izin resmi
                                        </li>
                                    </ul>
                                    <p style="font-size: 14px; color: #666; margin-top: 20px;">
                                        📈 Data ini membantu memantau konsistensi kehadiran siswa dan mendeteksi tren yang
                                        perlu perhatian khusus.
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="informasi-guru">
                        <!-- Informasi Guru -->
                        <div class="header-data"
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; margin-top: 30px;">
                            <h2>Informasi Guru</h2>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" style="background-color: #fff;">
                                <thead class="table-success">
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
                                                @forelse ($g->mataPelajaran ?? [] as $mp)
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


                    <div class="detail-kelas">
                        <!-- Detail Kelas -->
                        <div class="header-data"
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; margin-top:30px">
                            <h2>Detail Kelas</h2>
                        </div>

                        @if ($kelas && $kelas->count())
                            <div class="daftar-kelas" style="display: flex; flex-wrap: wrap; gap: 20px;">
                                @foreach ($kelas as $k)
                                    <div class="card"
                                        style="flex: 1 1 220px; padding: 20px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); background-color: #fff; display: flex; flex-direction: column; gap: 15px;">
                                        <div>
                                            {{-- <p style="margin: 0; font-size: 14px;">Nama Kelas</p> --}}
                                            {{-- <h3 style="margin: 0;">{{ $k->nama_kelas }}</h3> --}}
                                        </div>
                                        <div>
                                            {{-- <p style="margin: 0; font-size: 14px;">Guru Wali</p> --}}
                                            {{-- <h3 style="margin: 0;">{{ $k->guruWali->nama_lengkap ?? 'Tidak Ada' }}</h3> --}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Tidak ada data kelas.</p>
                        @endif

                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
