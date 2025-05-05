@extends('layouts.admin-layout')

@section('title', 'Rekapitulasi Kehadiran Kelas ' . $kelas->nama_kelas)

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">
                        <a href="{{ url('/rekapitulasi') }}" class="text-decoration-none text-success fw-semibold">
                            Rekapitulasi Absensi
                        </a>
                        <span class="fs-5 text-muted d-block d-sm-inline mt-1 mt-sm-0">/ {{ $kelas->nama_kelas }}</span>
                    </h1>
                    <p class="mb-2">
                        Rekapitulasi kehadiran siswa kelas {{ $kelas->nama_kelas }}
                        @if ($tahunAjaran)
                            ({{ $tahunAjaran->nama_tahun_ajaran }})
                        @endif
                    </p>
                </header>

                <div class="data">
                    <!-- Filter & Export Bar -->
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
                        <form method="GET" action="{{ url('rekapitulasi/kelas/' . $kelas->id_kelas) }}" 
                              class="d-flex flex-wrap align-items-center gap-2 mb-3 mb-lg-0">
                            <label for="bulan" class="me-2 mb-0">Bulan:</label>
                            <select name="bulan" id="bulan" class="form-select w-auto">
                                @foreach ($bulanList as $key => $namaBulan)
                                    <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                        {{ $namaBulan }}
                                    </option>
                                @endforeach
                            </select>

                            <label for="tahun" class="ms-3 me-2 mb-0">Tahun:</label>
                            <select name="tahun" id="tahun" class="form-select w-auto">
                                @foreach ($tahunList as $value)
                                    <option value="{{ $value }}" {{ $tahun == $value ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-primary ms-2">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>
                        </form>

                        <div>
                            <a href="{{ route('rekapitulasi.export.pdf', [
                                    'kelas' => $kelas->id_kelas, 
                                    'bulan' => $bulan, 
                                    'tahun' => $tahun
                                ]) }}" class="btn btn-danger me-2">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
                            </a>
                            <a href="{{ route('rekapitulasi.export.excel', [
                                    'kelas' => $kelas->id_kelas, 
                                    'bulan' => $bulan, 
                                    'tahun' => $tahun
                                ]) }}" class="btn btn-success">
                                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
                            </a>
                        </div>
                    </div>

                    <!-- Judul Rekapitulasi -->
                    <h5 class="mb-3">Rekapitulasi Kehadiran Bulan {{ $bulanList[$bulan] ?? '' }} {{ $tahun }}</h5>

                    <!-- Tabel Rekapitulasi -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th rowspan="2" class="align-middle text-center" style="min-width: 40px;">No</th>
                                    <th rowspan="2" class="align-middle" style="min-width: 150px;">Nama Siswa</th>
                                    <th colspan="4" class="text-center">Kehadiran</th>
                                    <th rowspan="2" class="align-middle text-center" style="min-width: 60px;">Total</th>
                                </tr>
                                <tr>
                                    <th class="text-center" style="min-width: 60px;">Hadir</th>
                                    <th class="text-center" style="min-width: 60px;">Sakit</th>
                                    <th class="text-center" style="min-width: 60px;">Izin</th>
                                    <th class="text-center" style="min-width: 60px;">Alpa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswaList as $index => $siswa)
                                    @php
                                        $rekapSiswa  = $rekapData[$siswa->id_siswa] ?? null;
                                        $jumlahHadir = $rekapSiswa->jumlah_hadir ?? 0;
                                        $jumlahSakit = $rekapSiswa->jumlah_sakit ?? 0;
                                        $jumlahIzin  = $rekapSiswa->jumlah_izin ?? 0;
                                        $jumlahAlpa  = $rekapSiswa->jumlah_alpa ?? 0;
                                        $total       = $jumlahHadir + $jumlahSakit + $jumlahIzin + $jumlahAlpa;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $siswa->nama }}</td>
                                        <td class="text-center">{{ $jumlahHadir }}</td>
                                        <td class="text-center">{{ $jumlahSakit }}</td>
                                        <td class="text-center">{{ $jumlahIzin }}</td>
                                        <td class="text-center">{{ $jumlahAlpa }}</td>
                                        <td class="text-center">{{ $total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data siswa</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <th colspan="2" class="text-end">Total:</th>
                                    <th class="text-center">{{ $rekapData->sum('jumlah_hadir') ?? 0 }}</th>
                                    <th class="text-center">{{ $rekapData->sum('jumlah_sakit') ?? 0 }}</th>
                                    <th class="text-center">{{ $rekapData->sum('jumlah_izin') ?? 0 }}</th>
                                    <th class="text-center">{{ $rekapData->sum('jumlah_alpa') ?? 0 }}</th>
                                    <th class="text-center">
                                        {{
                                            $rekapData->sum(function($item) {
                                                return ($item->jumlah_hadir ?? 0)
                                                     + ($item->jumlah_sakit ?? 0)
                                                     + ($item->jumlah_izin ?? 0)
                                                     + ($item->jumlah_alpa ?? 0);
                                            })
                                        }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection