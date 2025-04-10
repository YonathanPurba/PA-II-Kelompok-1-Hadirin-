@extends('layouts.admin-layout')

@section('title', 'Manajemen Data Guru')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/konten.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <div class="container">
        <main class="main-content">
            <div class="isi">
                <header class="judul">
                    <h1>Manajemen Data Guru</h1>
                    <p>Staff dapat menambah, melihat, dan mengubah data guru</p>
                </header>

                <div class="data">
                    <div class="header-data"
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2>Kelola Data Guru</h2>
                        <a href="{{ url('/admin/guru/tambah_guru') }}">
                            <button
                                style="padding: 8px 16px; background-color: #28a745; color: white; border: none; border-radius: 5px;">Tambah
                                Guru</button>
                        </a>
                    </div>

                    <div class="search" style="margin-bottom: 20px;">
                        <input type="text" placeholder="Cari data guru..." style="padding: 8px; width: 250px;">
                        <button style="padding: 8px 16px; margin-left: 10px;">Cari</button>
                    </div>

                    <div class="daftar-kelas" style="display: flex; flex-wrap: wrap; gap: 20px;">
                        @forelse ($gurus as $guru)
                            <div class="card"
                                style="width: 240px; height: auto; padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); background-color: #fff; display: flex; flex-direction: column; align-items: center;">
                                <!-- Icon Guru -->
                                <div style="font-size: 60px; color: #4e73df; margin-bottom: 10px;">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>

                                <div style="text-align: center;">
                                    <p><strong>{{ $guru->nama_lengkap ?? 'Tidak ada nama' }}</strong></p>
                                    <p>Wali Kelas <strong>{{ $guru->kelas->nama_kelas ?? '-' }}</strong></p>
                                </div>
                            </div>
                        @empty
                            <p style="padding: 10px;">Tidak ada data guru yang tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
