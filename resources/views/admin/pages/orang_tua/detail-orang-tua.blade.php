@extends('layouts.admin-layout')

@section('title', 'Detail Orang Tua')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <header class="judul mb-4">
                    <h1 class="mb-2">Kelas {{ $kelas->nama_kelas }}</h1>
                    <p class="text-muted">Daftar siswa dan orang tua di kelas ini</p>
                </header>

                <div class="data">

                    <a href="{{ route('orang-tua.index') }}" class="btn btn-outline-secondary mb-3">
                        ← Kembali ke Daftar Kelas
                    </a>

                    @if ($kelas->siswa->count())
                        <table class="table table-bordered">
                            <thead class="table-success">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Nama Orang Tua</th>
                                    <th>Nomor Telepon</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kelas->siswa as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $siswa->nama }}</td>
                                        <td>{{ $siswa->orangtua->nama_lengkap ?? '-' }}</td>
                                        <td>{{ $siswa->orangtua->nomor_telepon ?? '-' }}</td>
                                        {{-- <td>{{ $siswa->orangtua->email ?? '-' }}</td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Tidak ada siswa dalam kelas ini.</p>
                    @endif
                </div>
            </div>
        </main>
    </div>
@endsection
