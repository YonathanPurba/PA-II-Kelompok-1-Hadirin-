@extends('layouts.admin-layout')

@section('title', 'Manajemen Jadwal Pelajaran')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <!-- Header Judul -->
            <header class="judul">
                <h1 class="mb-3">Manajemen Jadwal Pelajaran</h1>
                <p class="mb-2">Staff dapat menambah, melihat, dan mengubah data jadwal pelajaran untuk semua kelas</p>
            </header>

            <div class="data">

                <!-- Filter Tahun Ajaran -->
               <div class="row mb-4">
    <div class="col-md-12">
        <form method="GET" action="{{ route('jadwal-pelajaran.index') }}" class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <label for="tahun_ajaran" class="me-2 mb-0">T/A:</label>
                <select name="tahun_ajaran" id="tahun_ajaran" class="form-select" style="min-width: 200px;">
                    <option value="">Semua Tahun Ajaran</option>
                    @foreach($tahunAjaranList as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}" 
                            {{ request('tahun_ajaran', $tahunAjaranAktif->id_tahun_ajaran ?? '') == $ta->id_tahun_ajaran ? 'selected' : '' }}
                            {{ $ta->aktif ? 'class=fw-bold text-success' : '' }}>
                            {{ $ta->nama_tahun_ajaran }} {{ $ta->aktif ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn btn-outline-success">
                <i class="bi bi-filter me-1"></i> Filter
            </button>

            @if(request()->has('tahun_ajaran'))
                <a href="{{ route('jadwal-pelajaran.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i> Reset
                </a>
            @endif

            <div class="ms-auto"> <!-- This div pushes the "Tambah" button to the right -->
                <a href="{{ route('jadwal-pelajaran.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Tambah
                </a>
            </div>
        </form>
    </div>
</div>


                <!-- Tampilan Jadwal -->
                <div class="table-responsive">
                    @if($jadwalList->isEmpty())
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i> Tidak ada jadwal pelajaran.
                        </div>
                    @else
                        <!-- Tampilan Jadwal dengan DataTable -->
                        <table id="jadwalTable" class="table table-striped table-bordered table-sm">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Hari</th>
                                    <th>Waktu</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <!-- <th>Status</th> -->
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwalList as $index => $jadwal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $jadwal->kelas->nama_kelas }}</td>
                                    <td>{{ ucfirst($jadwal->hari) }}</td>
                                    <td>{{ date('H:i', strtotime($jadwal->waktu_mulai)) }} - {{ date('H:i', strtotime($jadwal->waktu_selesai)) }}</td>
                                    <td>{{ $jadwal->mataPelajaran->nama }}</td>
                                    <td>{{ $jadwal->guru->nama_lengkap }}</td>
                                    <!-- <td>
                                        @if($jadwal->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-Aktif</span>
                                        @endif
                                    </td> -->
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-4">
                                            <a href="{{ route('jadwal-pelajaran.edit', $jadwal->id_jadwal) }}" 
                                                class="text-warning"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Edit">
                                                <i class="bi bi-pencil-square fs-5"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Detail Jadwal -->
<div class="modal fade" id="modalDetailJadwal" tabindex="-1" aria-labelledby="modalDetailJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalDetailJadwalLabel">Detail Jadwal Pelajaran</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-4">
                    <dt class="col-sm-4">Kelas</dt>
                    <dd class="col-sm-8" id="detail-kelas">-</dd>
                    
                    <dt class="col-sm-4">Mata Pelajaran</dt>
                    <dd class="col-sm-8" id="detail-mapel">-</dd>
                    
                    <dt class="col-sm-4">Guru</dt>
                    <dd class="col-sm-8" id="detail-guru">-</dd>
                    
                    <dt class="col-sm-4">Hari</dt>
                    <dd class="col-sm-8" id="detail-hari">-</dd>
                    
                    <dt class="col-sm-4">Waktu</dt>
                    <dd class="col-sm-8" id="detail-waktu">-</dd>
                    
                    <dt class="col-sm-4">Sesi</dt>
                    <dd class="col-sm-8" id="detail-sesi">-</dd>
                    
                    <dt class="col-sm-4">Tahun Ajaran</dt>
                    <dd class="col-sm-8" id="detail-tahun-ajaran">-</dd>
                    
                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8" id="detail-status">-</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        $('#jadwalTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                },
                zeroRecords: "Tidak ditemukan data yang cocok",
                emptyTable: "Tidak ada data tersedia"
            }
        });
        
        // Inisialisasi tooltip
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Lihat detail jadwal
        $('.btn-view-jadwal').on('click', function() {
            const id = $(this).data('id');
            
            // Ambil data jadwal dari server
            $.ajax({
                url: `/jadwal-pelajaran/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const jadwal = response.data;
                        
                        // Isi detail jadwal ke modal
                        $('#detail-kelas').text(jadwal.kelas.nama_kelas);
                        $('#detail-mapel').text(jadwal.mata_pelajaran.nama);
                        $('#detail-guru').text(jadwal.guru.nama_lengkap);
                        $('#detail-hari').text(jadwal.hari.charAt(0).toUpperCase() + jadwal.hari.slice(1));
                        $('#detail-waktu').text(formatTime(jadwal.waktu_mulai) + ' - ' + formatTime(jadwal.waktu_selesai));
                        
                        // Determine session number based on time
                        const sesiList = [
                            { start: '07:45', end: '08:30', sesi: 1 },
                            { start: '08:30', end: '09:15', sesi: 2 },
                            { start: '09:15', end: '10:00', sesi: 3 },
                            { start: '10:15', end: '11:00', sesi: 4 },
                            { start: '11:00', end: '11:45', sesi: 5 },
                            { start: '11:45', end: '12:30', sesi: 6 }
                        ];
                        
                        const startTime = formatTime(jadwal.waktu_mulai);
                        const endTime = formatTime(jadwal.waktu_selesai);
                        
                        let startSesi = 0;
                        let endSesi = 0;
                        
                        sesiList.forEach(sesi => {
                            if (sesi.start === startTime) startSesi = sesi.sesi;
                            if (sesi.end === endTime) endSesi = sesi.sesi;
                        });
                        
                        if (startSesi > 0 && endSesi > 0) {
                            if (startSesi === endSesi) {
                                $('#detail-sesi').text(`Sesi ${startSesi}`);
                            } else {
                                $('#detail-sesi').text(`Sesi ${startSesi} - ${endSesi}`);
                            }
                        } else {
                            $('#detail-sesi').text('-');
                        }
                        
                        $('#detail-tahun-ajaran').text(jadwal.tahun_ajaran ? jadwal.tahun_ajaran.nama_tahun_ajaran : '-');
                        
                        // Set status badge
                        if (jadwal.status === 'aktif') {
                            $('#detail-status').html('<span class="badge bg-success">Aktif</span>');
                        } else {
                            $('#detail-status').html('<span class="badge bg-secondary">Non-Aktif</span>');
                        }
                    } else {
                        alert('Gagal memuat data jadwal');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat memuat data jadwal');
                }
            });
        });
        
        // Format waktu dari format 24 jam ke format 12 jam
        function formatTime(time) {
            const timeParts = time.split(':');
            let hours = parseInt(timeParts[0]);
            const minutes = timeParts[1];
            
            return hours + ':' + minutes;
        }
    });
</script>
@endsection
