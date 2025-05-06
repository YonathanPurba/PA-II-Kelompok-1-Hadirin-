<!-- resources/views/exports/orangtua_pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Orang Tua</title>
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
    <h1>Data Orang Tua</h1>
    
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Lengkap</th>
                <th width="15%">Nomor Telepon</th>
                <th width="15%">Pekerjaan</th>
                <th width="30%">Nama Anak</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orangTuaList as $index => $orangTua)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $orangTua->nama_lengkap }}</td>
                    <td>{{ $orangTua->nomor_telepon ?? '-' }}</td>
                    <td>{{ $orangTua->pekerjaan ?? '-' }}</td>
                    <td>
                        @if($orangTua->siswa->count() > 0)
                            <ul style="margin: 0; padding-left: 15px;">
                                @foreach($orangTua->siswa as $anak)
                                    <li>{{ $anak->nama }} ({{ $anak->kelas->nama_kelas ?? 'Belum ada kelas' }})</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                    <td class="status-{{ $orangTua->status }}">
                        {{ ucfirst($orangTua->status) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data orang tua</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}
    </div>
</body>
</html>