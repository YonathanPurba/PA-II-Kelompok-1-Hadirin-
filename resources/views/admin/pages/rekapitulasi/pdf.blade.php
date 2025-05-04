<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Absensi {{ $kelas->nama_kelas }} - {{ $namaBulan }} {{ $tahun }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REKAPITULASI ABSENSI SISWA</h1>
        <p>Kelas: {{ $kelas->nama_kelas }}</p>
        <p>Bulan: {{ $namaBulan }} {{ $tahun }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 40px;">No</th>
                <th rowspan="2" class="text-left">Nama Siswa</th>
                <th colspan="4">Kehadiran</th>
                <th rowspan="2" style="width: 60px;">Total</th>
            </tr>
            <tr>
                <th style="width: 60px;">Hadir</th>
                <th style="width: 60px;">Sakit</th>
                <th style="width: 60px;">Izin</th>
                <th style="width: 60px;">Alpa</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHadir = 0;
                $totalSakit = 0;
                $totalIzin = 0;
                $totalAlpa = 0;
                $grandTotal = 0;
            @endphp
            
            @forelse ($siswaList as $index => $siswa)
                @php
                    $rekapSiswa = $rekapData[$siswa->id_siswa] ?? null;
                    $jumlahHadir = $rekapSiswa ? ($rekapSiswa->jumlah_hadir ?? 0) : 0;
                    $jumlahSakit = $rekapSiswa ? ($rekapSiswa->jumlah_sakit ?? 0) : 0;
                    $jumlahIzin = $rekapSiswa ? ($rekapSiswa->jumlah_izin ?? 0) : 0;
                    $jumlahAlpa = $rekapSiswa ? ($rekapSiswa->jumlah_alpa ?? 0) : 0;
                    $total = $jumlahHadir + $jumlahSakit + $jumlahIzin + $jumlahAlpa;
                    
                    $totalHadir += $jumlahHadir;
                    $totalSakit += $jumlahSakit;
                    $totalIzin += $jumlahIzin;
                    $totalAlpa += $jumlahAlpa;
                    $grandTotal += $total;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $siswa->nama }}</td>
                    <td>{{ $jumlahHadir }}</td>
                    <td>{{ $jumlahSakit }}</td>
                    <td>{{ $jumlahIzin }}</td>
                    <td>{{ $jumlahAlpa }}</td>
                    <td>{{ $total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data siswa</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">Total:</th>
                <th>{{ $totalHadir }}</th>
                <th>{{ $totalSakit }}</th>
                <th>{{ $totalIzin }}</th>
                <th>{{ $totalAlpa }}</th>
                <th>{{ $grandTotal }}</th>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>
        <p style="margin-top: 50px;">
            Kepala Sekolah
            <br><br><br>
            ______________________
            <br>
            (Nama Kepala Sekolah)
        </p>
    </div>
</body>
</html>
