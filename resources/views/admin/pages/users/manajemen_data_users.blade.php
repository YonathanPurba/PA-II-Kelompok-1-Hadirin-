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

                    <!-- Filter Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <form method="GET" action="{{ route('users.index') }}" class="d-flex align-items-center gap-3 flex-wrap">

                            
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" name="search" class="form-control" placeholder="Cari username..." 
                                    value="{{ request('search') }}">
                            </div>
                            
                            <button type="submit" class="btn btn-outline-success">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>
                            
                            @if(request()->has('role') || request()->has('status') || request()->has('search'))
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Reset
                                </a>
                            @endif
                        </form>
                    </div>

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

                    <!-- Pagination jika menggunakan paginate -->
                    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center mt-3">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @endif
                    
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

    @section('scripts')
    <script>
        $(document).ready(function() {
            // DataTables can cause column count issues, so we'll disable it
            // and rely on Laravel's built-in pagination
            /*
            if (!$.fn.DataTable.isDataTable('#usersTable')) {
                $('#usersTable').DataTable({
                    responsive: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json',
                    },
                    columnDefs: [
                        { orderable: false, targets: 5 }
                    ],
                    paging: false,
                    searching: false,
                    info: false
                });
            }
            */

            // Handle view user button click
            $('.btn-view-user').on('click', function(e) {
                e.preventDefault(); // Prevents any default navigation
                const id = $(this).data('id');
                
                // Show loading state
                $('#view-username').text('Memuat...');
                $('#view-role').text('Memuat...');
                $('#view-created').text('Memuat...');
                $('#view-last-login').text('Memuat...');
                $('#table-akses-body').html('<tr><td colspan="3" class="text-center">Memuat data...</td></tr>');
                
                // Mengambil data secara langsung dari atribut data
                const userData = {
                    username: $(this).data('username') || '-',
                    role: $(this).data('role') || '-',
                    created: $(this).data('created') || '-',
                    last_login: $(this).data('last-login') || '-',
                };

                // Fill in basic details dari atribut data
                $('#view-username').text(userData.username);
                $('#view-role').text(userData.role);
                $('#view-created').text(userData.created);
                $('#view-last-login').text(userData.last_login);
                
                
                // Tampilkan modal
                $('#modalViewUser').modal('show');
                
                // Coba ambil data hak akses via AJAX
                $.ajax({
                    url: `/users/${id}/akses`,
                    method: 'GET',
                    success: function(response) {
                        // Fill in akses table
                        if (response && response.length > 0) {
                            let tableContent = '';
                            response.forEach((akses, index) => {
                                // Create status badge based on access status
                                let aksesBadge = akses.has_access ? 
                                    '<span class="badge bg-success"><i class="bi bi-check-lg"></i> Ya</span>' : 
                                    '<span class="badge bg-secondary"><i class="bi bi-x-lg"></i> Tidak</span>';
                                
                                tableContent += `
                                    <tr>
                                        <td class="text-center">${index + 1}</td>
                                        <td>${akses.modul_name}</td>
                                        <td class="text-center">${aksesBadge}</td>
                                    </tr>
                                `;
                            });
                            $('#table-akses-body').html(tableContent);
                        } else {
                            $('#table-akses-body').html('<tr><td colspan="3" class="text-center">Tidak ada data hak akses</td></tr>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching access data:', xhr);
                        $('#table-akses-body').html('<tr><td colspan="3" class="text-center">Tidak ada data hak akses</td></tr>');
                    }
                });
            });
        });
    </script>
    @endsection