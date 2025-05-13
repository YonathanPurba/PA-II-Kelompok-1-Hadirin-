    @extends('layouts.admin-layout')

    @section('title', 'Manajemen Data Pengguna')

    @section('content')
    <div class="container-fluid">
        <main class="main-content">
            <div class="isi">
                <!-- Header Judul -->
                <header class="judul">
                    <h1 class="mb-3">Manajemen Data Pengguna</h1>
                    <p class="mb-2">Staff dapat melihat dan mengubah data pengguna sistem</p>
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
                        <table id="usersTable" class="table table-striped table-bordered table-sm align-middle">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Username</th>
                                    <th width="20%">Role</th>
                                    <th width="20%">Terakhir Login</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->role->role ?? '-' }}</td>
                                        <td>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d-m-Y H:i') : '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Ubah semua tombol view untuk selalu menggunakan modal -->
                                                <a href="{{ route('users.show', $user->id_user) }}" class="text-primary" title="Lihat">
                                                        <i class="bi bi-eye-fill fs-5"></i>
                                                    </a>


                                                <a href="{{ route('users.edit', $user->id_user) }}" class="text-warning" title="Edit">
                                                    <i class="bi bi-pencil-square fs-5"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">Tidak ada data pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>                    
                </div>
            </div>
        </main>
    </div>

    <!-- Modal View User -->
    <div class="modal fade" id="modalViewUser" tabindex="-1" aria-labelledby="modalViewUserLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="modal-title" id="modalViewUserLabel">Detail Pengguna</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <dl class="row mb-4">
                        <dt class="col-sm-4">Username</dt>
                        <dd class="col-sm-8" id="view-username">-</dd>

                        <dt class="col-sm-4">Role</dt>
                        <dd class="col-sm-8" id="view-role">-</dd>
            
                        <dt class="col-sm-4">Tanggal Dibuat</dt>
                        <dd class="col-sm-8" id="view-created">-</dd>

                        <dt class="col-sm-4">Terakhir Login</dt>
                        <dd class="col-sm-8" id="view-last-login">-</dd>
                    </dl>

                    <hr>

                    <h5 class="mb-3">Hak Akses</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Modul</th>
                                    <th>Akses</th>
                                </tr>
                            </thead>
                            <tbody id="table-akses-body">
                                <tr>
                                    <td colspan="3" class="text-center">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endsection