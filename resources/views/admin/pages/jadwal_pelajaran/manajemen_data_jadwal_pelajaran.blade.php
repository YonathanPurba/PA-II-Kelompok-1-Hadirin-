@extends('layouts.admin-layout')

@section('title', 'Lihat Jadwal Pelajaran')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <header class="judul mb-4">
                <h1 class="mb-3">Manajemen Jadwal</h1>
                <p class="mb-2">Halaman untuk melihat semua jadwal pelajaran berdasarkan kelas</p>
            </header>

            <div class="data">

                <!-- Filter Kelas -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <form method="GET" action="{{ route('jadwal-pelajaran.index') }}" class="d-flex align-items-center gap-2">
                        <label for="id_kelas" class="form-label">Filter kelas:</label>
                        <div class="d-flex gap-3 align-items-center">
                            <select name="id_kelas" id="id_kelas" class="form-select w-auto" onchange="this.form.submit()">
                                <option value="">-- Semua Kelas --</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Tabel Jadwal Per Hari -->
                @php
                    $groupedJadwal = $jadwal->groupBy('hari');
                    $dayOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                @endphp

                @foreach ($dayOrder as $day)
                    @if ($groupedJadwal->has($day))
                        <div class="mb-4">
                            <h5 class="mb-3 text-capitalize">{{ $day }}</h5>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 10%">Jam Ke</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Guru</th>
                                            <th>Kelas</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($groupedJadwal[$day]->sortBy('waktu_mulai') as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->mataPelajaran->nama ?? '-' }}</td>
                                                <td>{{ $item->guru->nama_lengkap ?? '-' }}</td>
                                                <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if ($jadwal->isEmpty())
                    <div class="text-center text-muted mt-4">Belum ada data jadwal</div>
                @endif

            </div>
        </div>
    </main>
</div>
@endsection
