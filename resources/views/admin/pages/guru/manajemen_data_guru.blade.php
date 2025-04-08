@extends('layouts.admin-layout')

@section('title', 'manajemen_data_guru')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/konten.css') }}">

    <div class="container">
        <main class="main-content">
            <div class="isi">
                <header class="judul">
                    <h1>Manajemen Data Guru</h1>
                    <p>Staff Dapat menambah, Melihat, dan Mengubah Data Guru</p>
                </header>
                <div class="data">
                    <div class="header-data">
                        <h2>Kelola Data Guru</h2>
                        <button><a href="{{ url('/admin/guru/tambah_guru') }}">Tambah Guru</a></button>
                    </div>
                    <div class="search">
                        <input type="text" placeholder="Cari data guru...">
                        <button>Cari</button>
                    </div>
                    <div class="daftar-kelas">
                        @foreach ($gurus as $guru)
                            <div class="card">
                                <p><strong>Nama:</strong> {{ $guru->user->nama ?? 'Tidak ada nama' }}</p>
                                <p><strong>NIP:</strong> {{ $guru->nip ?? '-' }}</p>
                                <p><strong>Bidang Studi:</strong> {{ $guru->bidang_studi ?? '-' }}</p>
                            </div>
                        @endforeach

                        @if ($gurus->isEmpty())
                            <p>Tidak ada data guru yang tersedia.</p>
                        @endif
                    </div>

                </div>
            </div>
        </main>
    </div>
@endsection
