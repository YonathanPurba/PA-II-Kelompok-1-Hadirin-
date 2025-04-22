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
                        <form method="GET" action="{{ route('jadwal-pelajaran.index') }}"
                            class="d-flex align-items-center gap-2">
                            <label for="id_kelas" class="form-label">Filter kelas:</label>
                            <div class="d-flex gap-3 align-items-center">
                                <select name="id_kelas" id="id_kelas" class="form-select w-auto"
                                    onchange="this.form.submit()">
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id_kelas }}"
                                            {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>

                    @php
                        $groupedJadwal = $jadwal->groupBy('hari');
                        $dayOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

                        // Array warna untuk guru
                        $guruColors = [
                            '#E3F2FD',
                            '#FCE4EC',
                            '#E8F5E9',
                            '#FFF3E0',
                            '#F3E5F5',
                            '#FFEBEE',
                            '#E0F7FA',
                            '#F1F8E9',
                            '#FFFDE7',
                            '#ECEFF1',
                        ];
                        $guruColorMap = [];
                        $colorIndex = 0;

                        $dataBaris = collect();
                        foreach ($dayOrder as $day) {
                            if ($groupedJadwal->has($day)) {
                                $sorted = $groupedJadwal[$day]->sortBy('waktu_mulai');
                                foreach ($sorted as $i => $item) {
                                    $guruId = $item->id_guru;
                                    if (!isset($guruColorMap[$guruId])) {
                                        // Map guruId ke warna
                                        $guruColorMap[$guruId] = $guruColors[$colorIndex % count($guruColors)];
                                        $colorIndex++;
                                    }
                                    $dataBaris->push([
                                        'jadwal' => $item,
                                        'hari' => $day,
                                        'isFirstOfDay' => $i === 0,
                                        'guruColor' => $guruColorMap[$guruId],
                                    ]);
                                }
                            }
                        }
                    @endphp

                    @if ($dataBaris->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">Hari</th>
                                        <th style="width: 8%">Jam Ke</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru</th>
                                        @if (!request('id_kelas'))
                                            <th>Kelas</th>
                                        @endif
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $currentDay = null;
                                        $jamKe = 1;
                                    @endphp
                                    @foreach ($dataBaris as $baris)
                                        @php
                                            $jadwal = $baris['jadwal'];
                                            $hari = $baris['hari'];
                                            $showHari = $currentDay !== $hari;
                                            if ($showHari) {
                                                $currentDay = $hari;
                                                $jamKe = 1;
                                            }
                                            $rowColor = $baris['guruColor']; // Warna berdasarkan guru
                                            $isFirstOfDay = $baris['isFirstOfDay']; // Cek apakah baris ini yang pertama di hari tersebut
                                        @endphp
                                        <tr style="{{ $isFirstOfDay ? 'border-top: 3px solid black;' : '' }}">
                                            <td>
                                                @if ($showHari)
                                                    {{ ucfirst($hari) }}
                                                @endif
                                            </td>
                                            <td style="background-color: {{ $rowColor }};">{{ $jamKe++ }}</td>
                                            <td style="background-color: {{ $rowColor }};">
                                                {{ $jadwal->mataPelajaran->nama ?? '-' }}
                                            </td>
                                            <td style="background-color: {{ $rowColor }};">
                                                {{ $jadwal->guru->nama_lengkap ?? '-' }}
                                            </td>
                                            @if (!request('id_kelas'))
                                                <td style="background-color: {{ $rowColor }};">
                                                    {{ $jadwal->kelas->nama_kelas ?? '-' }}
                                                </td>
                                            @endif
                                            <td style="background-color: {{ $rowColor }};">
                                                {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted mt-4">Belum ada data jadwal</div>
                    @endif

                </div>
            </div>
        </main>
    </div>
@endsection
