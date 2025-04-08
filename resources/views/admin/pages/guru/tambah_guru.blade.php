@extends('layouts.admin-layout')

@section('title', 'tambah_guru')

@section('content')
<link rel="stylesheet" href="{{ asset('css/konten.css') }}">

<div class="container">
    <div class="main-content">
        <div class="isi">
            <div class="judul">
                <h1>Tambah Guru</h1>
                <p>Staff Dapat Menambah Data Guru Baru</p>
            </div>
            <div class="data">
                <div class="isi-data">
                    <div class="data-guru">
                        <h1>Data Guru baru</h1>
                        <div class="data-guru-baru">
                            <label for="nama-guru">Nama Guru</label>
                            <p>:</p>
                            <input type="text" id="namaGuru" name="nameGuru" placeholder="Masukkan Nama...">
                        </div>
                        <div class="data-guru-baru">
                            <label for="jenis-kelamin">Jenis Kelamin</label>
                            <p>:</p>
                            <div class="radio-group">
                                <input type="radio" id="pria" name="jenisKelamin" value="Pria">Pria
                                <input type="radio" id="wanita" name="jenisKelamin" value="Wanita">Wanita
                            </div>
                        </div>
                        <div class="data-guru-baru">
                            <label for="nip-guru">NIP Guru</label>
                            <p>:</p>
                            <input type="text" id="nipGuru" name="nipGuru" placeholder="Masukkan NIP...">
                        </div>
                        <div class="data-guru-baru">
                            <label for="nomor-telepon" id="nomor-telepon" name="nomorTelepon">Nomor Telepon</label>
                            <p>:</p>
                            <input type="text" id="nomorTelepon" name="nomorTelepon" placeholder="Masukkan No HP">
                        </div>
                        <div class="data-guru-baru">
                            <label for="alamatGuru">Alamat Guru</label>
                            <p>:</p>
                            <textarea id="alamatGuru" name="alamatGuru" placeholder="Masukkan Alamat..."></textarea>
                        </div>
                        <div class="data-guru-baru">
                            <label for="fotoProfil">Foto Profil</label>
                            <p>:</p>
                            <input type="file" id="fotoProfil" name="fotoProfil">
                        </div>
                    </div>

                    <div class="username-pw">
                        <h1>Username dan Password Guru</h1>
                        <div class="username-pw-baru">
                            <label for="usernameGuru">Username</label>
                            <p>:</p>
                            <input type="text" id="usernameGuru" name="usernameGuru" placeholder="Masukkan Username...">
                        </div>
                        <div class="username-pw-baru">
                            <label for="passwordGuru">Password</label>
                            <p>:</p>
                            <input type="password" id="passwordGuru" name="passwordGuru" placeholder="Masukkan Password...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection