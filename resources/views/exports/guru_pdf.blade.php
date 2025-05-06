<!-- resources/views/exports/guru_pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Guru</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-aktif {
            color: green;
            font-weight: bold;
        }
        .status-nonaktif {
            color: gray;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h1>Data Guru</h1>
    
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Lengkap</th>
                <th width="15%">NIP</th>
                <th width="15%">Nomor Telepon</th>
                <th width="25%">Mata Pelajaran</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gurus as $index => $guru)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $guru->nama_lengkap }}</td>
                    <td>{{ $guru->nip ?? '-' }}</td>
                    <td>{{ $guru->nomor_telepon ?? '-' }}</td>
                    <td>
                        @if($guru->mataPelajaran && $guru->mataPelajaran->count() > 0)
                            {{ $guru->mataPelajaran->pluck('nama')->join(', ') }}
                        @else
                            {{ $guru->bidang_studi ?? '-' }}
                        @endif
                    </td>
                    <td class="status-{{ $guru->status }}">
                        {{ ucfirst($guru->status) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data guru</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}
    </div>
</body>
</html>