@extends('layouts.admin-layout')

@section('title', 'Detail Pengguna')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul mb-4">
                    <h1 class="mb-3">
                        <a href="{{ route('users.index') }}" class="text-decoration-none text-success fw-semibold">
                            Manajemen Data Pengguna
                        </a>
                        <span class="fs-5 text-muted">/ Detail Pengguna</span>
                    </h1>
                    <p class="mb-2">Informasi detail pengguna</p>
                </header>

                <div class="data">
                    <div class="p-4 rounded-4 bg-white shadow-sm">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2 mb-3">Informasi Akun</h5>
                                <dl class="row">
                                    <dt class="col-sm-4">Username</dt>
                                    <dd class="col-sm-8">{{ $user->username }}</dd>

                                    <dt class="col-sm-4">Role</dt>
                                    <dd class="col-sm-8">{{ $user->role->role ?? '-' }}</dd>

                                    <dt class="col-sm-4">Terakhir Login</dt>
                                    <dd class="col-sm-8">
                                        {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d-m-Y H:i') : '-' }}
                                    </dd>

                                    <dt class="col-sm-4">Dibuat Pada</dt>
                                    <dd class="col-sm-8">
                                        {{ $user->dibuat_pada ? \Carbon\Carbon::parse($user->dibuat_pada)->format('d-m-Y H:i') : '-' }}
                                    </dd>

                                    <dt class="col-sm-4">Dibuat Oleh</dt>
                                    <dd class="col-sm-8">{{ $user->dibuat_oleh ?? '-' }}</dd>

                                    <dt class="col-sm-4">Diperbarui Pada</dt>
                                    <dd class="col-sm-8">
                                        {{ $user->diperbarui_pada ? \Carbon\Carbon::parse($user->diperbarui_pada)->format('d-m-Y H:i') : '-' }}
                                    </dd>

                                    <dt class="col-sm-4">Diperbarui Oleh</dt>
                                    <dd class="col-sm-8">{{ $user->diperbarui_oleh ?? '-' }}</dd>
                                </dl>
                            </div>

                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2 mb-3">Informasi Profil</h5>
                                @if($user->guru)
                                    <dl class="row">
                                        <dt class="col-sm-4">Tipe Profil</dt>
                                        <dd class="col-sm-8">Guru</dd>

                                        <dt class="col-sm-4">Nama Lengkap</dt>
                                        <dd class="col-sm-8">{{ $user->guru->nama }}</dd>

                                        <dt class="col-sm-4">NIP</dt>
                                        <dd class="col-sm-8">{{ $user->guru->nip ?? '-' }}</dd>
                                    </dl>
                                @elseif($user->orangtua)
                                    <dl class="row">
                                        <dt class="col-sm-4">Tipe Profil</dt>
                                        <dd class="col-sm-8">Orang Tua</dd>

                                        <dt class="col-sm-4">Nama Lengkap</dt>
                                        <dd class="col-sm-8">{{ $user->orangtua->nama_lengkap }}</dd>

                                        <dt class="col-sm-4">Alamat</dt>
                                        <dd class="col-sm-8">{{ $user->orangtua->alamat ?? '-' }}</dd>

                                        <dt class="col-sm-4">Pekerjaan</dt>
                                        <dd class="col-sm-8">{{ $user->orangtua->pekerjaan ?? '-' }}</dd>
                                    </dl>
                                @elseif($user->staf)
                                    <dl class="row">
                                        <dt class="col-sm-4">Tipe Profil</dt>
                                        <dd class="col-sm-8">Staf</dd>

                                        <dt class="col-sm-4">Nama Lengkap</dt>
                                        <dd class="col-sm-8">{{ $user->staf->nama_lengkap }}</dd>

                                        <dt class="col-sm-4">NIP</dt>
                                        <dd class="col-sm-8">{{ $user->staf->nip ?? '-' }}</dd>

                                        <dt class="col-sm-4">Jabatan</dt>
                                        <dd class="col-sm-8">{{ $user->staf->jabatan ?? '-' }}</dd>
                                    </dl>
                                @else
                                    <p class="text-muted">Tidak ada profil terkait</p>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <div>
                                <a href="{{ route('users.edit', $user->id_user) }}" class="btn btn-warning me-2">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
