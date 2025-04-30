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
