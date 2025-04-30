@extends('layouts.admin-layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- Header -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detail User</h3>
                    <a href="{{ route('users.index') }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <!-- User Detail -->
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px">ID User</th>
                            <td>{{ $user->id_user ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>{{ $user->username ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>{{ $user->role->role ?? 'Tidak ada role' }}</td>
                        </tr>
                        <tr>
                            <th>Last Login</th>
                            <td>{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i:s') : 'Tidak ada data' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>{{ $user->dibuat_pada ? \Carbon\Carbon::parse($user->dibuat_pada)->format('d/m/Y H:i:s') : 'Tidak ada data' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>{{ $user->dibuat_oleh ?? 'Tidak ada data' }}</td>
                        </tr>
                        <tr>
                            <th>Diperbarui Pada</th>
                            <td>{{ $user->diperbarui_pada ? \Carbon\Carbon::parse($user->diperbarui_pada)->format('d/m/Y H:i:s') : 'Tidak ada data' }}</td>
                        </tr>
                        <tr>
                            <th>Diperbarui Oleh</th>
                            <td>{{ $user->diperbarui_oleh ?? 'Tidak ada data' }}</td>
                        </tr>
                    </table>

                    <!-- Tab Data Terkait -->
                    <div class="mt-4">
                        <h5>Data Terkait</h5>
                        <ul class="nav nav-tabs" id="relationTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="guru-tab" data-toggle="tab" href="#guru" role="tab">Guru</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="orangtua-tab" data-toggle="tab" href="#orangtua" role="tab">Orang Tua</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="staf-tab" data-toggle="tab" href="#staf" role="tab">Staf</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="relationTabsContent">
                            <!-- Guru -->
                            <div class="tab-pane fade show active" id="guru" role="tabpanel">
                                @if($user->guru)
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>NIP</th>
                                            <td>{{ $user->guru->nip ?? 'Tidak ada data' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>{{ $user->guru->nama ?? 'Tidak ada data' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Kelamin</th>
                                            <td>
                                                @if($user->guru->jenis_kelamin === 'L')
                                                    Laki-laki
                                                @elseif($user->guru->jenis_kelamin === 'P')
                                                    Perempuan
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>{{ $user->guru->alamat ?? 'Tidak ada data' }}</td>
                                        </tr>
                                    </table>
                                @else
                                    <p>Tidak ada data guru terkait dengan user ini.</p>
                                @endif
                            </div>

                            <!-- Orang Tua -->
                            <div class="tab-pane fade" id="orangtua" role="tabpanel">
                                @if($user->orangtua)
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Nama Lengkap</th>
                                            <td>{{ $user->orangtua->nama_lengkap ?? 'Tidak ada data' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>{{ $user->orangtua->alamat ?? 'Tidak ada data' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pekerjaan</th>
                                            <td>{{ $user->orangtua->pekerjaan ?? 'Tidak ada data' }}</td>
                                        </tr>
                                    </table>
                                @else
                                    <p>Tidak ada data orang tua terkait dengan user ini.</p>
                                @endif
                            </div>

                            <!-- Staf -->
                            <div class="tab-pane fade" id="staf" role="tabpanel">
                                @if($user->staf)
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>NIP</th>
                                            <td>{{ $user->staf->nip ?? 'Tidak ada data' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>{{ $user->staf->nama ?? 'Tidak ada data' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jabatan</th>
                                            <td>{{ $user->staf->jabatan ?? 'Tidak ada data' }}</td>
                                        </tr>
                                    </table>
                                @else
                                    <p>Tidak ada data staf terkait dengan user ini.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Button -->
                <div class="card-footer">
                    <div class="btn-group">
                        <a href="{{ route('users.edit', $user->id_user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus user <strong>{{ $user->username }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('users.destroy', $user->id_user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
