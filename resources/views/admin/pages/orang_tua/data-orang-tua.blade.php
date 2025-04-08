@extends('layouts.admin-layout')

@section('title', 'data-orant-tua')

@section('content')
<link rel="stylesheet" href="{{ asset('css/konten.css') }}">

<div class="container">
    <main class="main-content">
        <div class="isi">
            <div class="data">
                <div class="header-data">
                    <h2>Kelola Data Orang Tua</h2>
                    <button><a href="{{ url('/admin/orang_tua/tambah-orang-tua') }}">Tambah Orang Tua</a></button>
                </div>
                <div class="search">
                    <input type="text" placeholder="Cari Orang Tua...">
                    <button>Cari</button>
                </div>
                <div class="tabel-orang-tua">
                    <table border="1" class="tabel">
                        <tr>
                            <th></th>
                            <th>Nama Orang Tua</th>
                            <th>Nomor Telepon</th>
                            <th>Nama Anak</th>
                            <th>Aksi</th>
                        </tr>
                        <tr class="pilihan">
                            <th><input type="checkbox"></th>
                            <th>Jonathan Sitorus</th>
                            <th>0852-8245-4373</th>
                            <th>Joelsa Sitorus</th>
                            <th>Aksi</th>
                        </tr>
                        <tr class="pilihan">
                            <th><input type="checkbox"></th>
                            <th>Jonathan Sitorus</th>
                            <th>0852-8245-4373</th>
                            <th>Joelsa Sitorus</th>
                            <th>Aksi</th>
                        </tr>
                        <tr class="pilihan">
                            <th><input type="checkbox"></th>
                            <th>Jonathan Sitorus</th>
                            <th>0852-8245-4373</th>
                            <th>Joelsa Sitorus</th>
                            <th>Aksi</th>
                        </tr>
                        <tr class="pilihan">
                            <th><input type="checkbox"></th>
                            <th>Jonathan Sitorus</th>
                            <th>0852-8245-4373</th>
                            <th>Joelsa Sitorus</th>
                            <th>Aksi</th>
                        </tr>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection