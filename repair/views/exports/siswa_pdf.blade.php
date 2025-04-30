<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Data Siswa</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswaList as $index => $siswa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td>{{ $siswa->jenis_kelamin }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
