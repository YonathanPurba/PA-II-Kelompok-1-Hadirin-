@extends('layouts.admin-layout')

@section('title', 'manajemen_data_siswa')

@section('content')
<link rel="stylesheet" href="{{ asset('css/konten.css') }}">


<div class="container">
    <div class="main-content">
        <div class="isi">
            <header class="judul">
                <h1>Manajemen Data Siswa</h1>
                <p>Staff Dapat menambah, Melihat, dan Mengubah Data Siswa</p>
            </header>
            <div class="data">
                <center>
                    <h2>Daftar Kelas</h2>
                </center>
                <div class="daftar-kelas">
                    <a href="{{ url('admin/siswa/data-siswa') }}">
                        <div class="card">
                            <h2>7-A</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/siswa/data-siswa') }}">
                        <div class="card">
                            <h2>7-B</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/siswa/data-siswa') }}">
                        <div class="card">
                            <h2>7-C</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/siswa/data-siswa') }}">
                        <div class="card">
                            <h2>7-D</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/siswa/data-siswa') }}">
                        <div class="card">
                            <h2>8-A</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/siswa/data-siswa') }}">
                        <div class="card">
                            <h2>8-B</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/siswa/data-siswa') }}">
                        <div class="card">
                            <h2>8-C</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/siswa/data-siswa') }}">
                        <div class="card">
                            <h2>8-D</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/siswa/data-siswa') }}">
                        <div class="card">
                            <h2>9-A</h2>
                        </div>
                    </a>
                    <a href="{{ url('admin/siswa/data-siswa') }}">
                        <div class="card">
                            <h2>9-B</h2>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection