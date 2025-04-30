@extends('layouts.admin-layout')

@section('title', 'Manajemen Data Pengguna')

@section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Data Pengguna</h1>
                    <p class="mb-2">Staff dapat menambah, melihat, dan mengubah data pengguna sistem</p>
                </header>
                <div class="data">
                    <!-- Alert Success -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Alert Error -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Tabel Data Pengguna -->
                    <div class="table-responsive">
                        <table id="usersTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Terakhir Login</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->role->role ?? '-' }}</td>
                                        <td>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d-m-Y H:i') : '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-4">
                                                <a href="{{ route('users.show', $user->id_user) }}" class="text-primary" title="Lihat">
                                                    <i class="bi bi-eye-fill fs-5"></i>
                                                </a>

                                                <a href="{{ route('users.edit', $user->id_user) }}" class="text-warning"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>

                                                <form action="{{ route('users.destroy', $user->id_user) }}" method="POST" class="d-inline" 
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-danger border-0 bg-transparent p-0" title="Hapus">
                                                        <i class="bi bi-trash-fill fs-5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('users.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Pengguna
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
