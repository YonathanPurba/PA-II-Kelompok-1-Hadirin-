@extends('layouts.admin-layout')

@section('title', 'manajemen_data_orang_tua')

@section('content')
<link rel="stylesheet" href="{{ asset('css/konten.css') }}">

<div class="container">
    <main class="main-content">
        <div class="isi">
            <header class="judul">
                <h1>Manajemen Data Orang Tua / Wali</h1>
                <p>Staff Dapat menambah, Melihat, dan Mengubah Data Orang Tua atau Wali</p>
            </header>
            <div class="data">
                <div class="header-data">
                    <h2>Kelola Data Orang Tua / Wali</h2>
                </div>

                <div class="daftar-kelas">
                    <a href="{{ url('admin/orang_tua/data-orang-tua') }}">
                        <div class="card">
                            <h2>7-A</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/orang_tua/data-orang-tua') }}">
                        <div class="card">
                            <h2>7-B</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/orang_tua/data-orang-tua') }}">
                        <div class="card">
                            <h2>7-C</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/orang_tua/data-orang-tua') }}">
                        <div class="card">
                            <h2>7-D</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/orang_tua/data-orang-tua') }}">
                        <div class="card">
                            <h2>8-A</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/orang_tua/data-orang-tua') }}">
                        <div class="card">
                            <h2>8-B</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/orang_tua/data-orang-tua') }}">
                        <div class="card">
                            <h2>8-C</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/orang_tua/data-orang-tua') }}">
                        <div class="card">
                            <h2>8-D</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/orang_tua/data-orang-tua') }}">
                        <div class="card">
                            <h2>9-A</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/orang_tua/data-orang-tua') }}">
                        <div class="card">
                            <h2>9-B</h2>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection