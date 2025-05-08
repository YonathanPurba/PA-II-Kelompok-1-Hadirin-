@extends('layouts.admin-layout')

@section('title', 'Tambah Jadwal Pelajaran Massal')

@section('content')
<div class="container-fluid">
    <main class="main-content">
        <div class="isi">
            <!-- Header Judul -->
            <header class="judul">
                <h1 class="mb-3">Tambah Jadwal Pelajaran Massal</h1>
                <p class="mb-2">Buat banyak jadwal pelajaran sekaligus dengan mudah</p>
            </header>

            <div class="data">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form id="formJadwalMassal">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_tahun_ajaran" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                                        <select id="id_tahun_ajaran" name="id_tahun_ajaran" class="form-select" required>
                                            @foreach ($tahunAjaranList as $ta)
                                                <option value="{{ $ta->id_tahun_ajaran }}" {{ $ta->aktif ? 'selected' : '' }}>
                                                    {{ $ta->nama_tahun_ajaran }}
                                                    @if($ta->aktif)
                                                        - Aktif
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status Jadwal <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="form-select" required>
                                            <option value="aktif" selected>Aktif</option>
                                            <option value="nonaktif">Non-Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <strong>Petunjuk:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Tambahkan baris jadwal sebanyak yang Anda butuhkan dengan tombol "Tambah Baris"</li>
                                    <li>Isi semua kolom yang diperlukan untuk setiap jadwal</li>
                                    <li>Sistem akan otomatis memeriksa bentrok jadwal</li>
                                    <li>Klik "Simpan Semua Jadwal" untuk menyimpan semua jadwal sekaligus</li>
                                </ul>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="bg-success text-white">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Kelas</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Guru</th>
                                            <th>Hari</th>
                                            <th>Sesi</th>
                                            <th width="5%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jadwalRows">
                                        <tr id="jadwalRow1">
                                            <td>1</td>
                                            <td>
                                                <select name="jadwal[0][id_kelas]" class="form-select jadwal-kelas" required>
                                                    <option value="">-- Pilih Kelas --</option>
                                                    @foreach ($kelas as $k)
                                                        <option value="{{ $k->id_kelas }}">
                                                            {{ $k->nama_kelas }}
                                                            @if($k->tahunAjaran)
                                                                ({{ $k->tahunAjaran->nama_tahun_ajaran }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="jadwal[0][id_mata_pelajaran]" class="form-select jadwal-mapel" required>
                                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                                    @foreach ($mataPelajaran as $mp)
                                                        <option value="{{ $mp->id_mata_pelajaran }}">{{ $mp->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="jadwal[0][id_guru]" class="form-select jadwal-guru" required>
                                                    <option value="">-- Pilih Guru --</option>
                                                    @foreach ($guru as $g)
                                                        <option value="{{ $g->id_guru }}">{{ $g->nama_lengkap }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="jadwal[0][hari]" class="form-select jadwal-hari" required>
                                                    <option value="">-- Pilih Hari --</option>
                                                    @foreach ($schoolDays as $day)
                                                        <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="jadwal[0][session]" class="form-select jadwal-session" required>
                                                    <option value="">-- Pilih Sesi --</option>
                                                    @foreach ($timeSlots as $index => $slot)
                                                        <option value="{{ $index + 1 }}">{{ $slot['label'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete-row" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" id="btnAddRow" class="btn btn-success">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Baris
                                </button>
                                <div>
                                    <a href="{{ route('jadwal-pelajaran.index') }}" class="btn btn-secondary me-2">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Simpan Semua Jadwal
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Initialize select2 for better dropdown experience
        $('#id_tahun_ajaran, #status').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
        
        initializeSelect2ForRow(0);
        
        // Add new row
        $('#btnAddRow').on('click', function() {
            const rowCount = $('#jadwalRows tr').length;
            const newRowIndex = rowCount;
            
            const newRow = `
                <tr id="jadwalRow${newRowIndex + 1}">
                    <td>${newRowIndex + 1}</td>
                    <td>
                        <select name="jadwal[${newRowIndex}][id_kelas]" class="form-select jadwal-kelas" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id_kelas }}">
                                    {{ $k->nama_kelas }}
                                    @if($k->tahunAjaran)
                                        ({{ $k->tahunAjaran->nama_tahun_ajaran }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="jadwal[${newRowIndex}][id_mata_pelajaran]" class="form-select jadwal-mapel" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($mataPelajaran as $mp)
                                <option value="{{ $mp->id_mata_pelajaran }}">{{ $mp->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="jadwal[${newRowIndex}][id_guru]" class="form-select jadwal-guru" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($guru as $g)
                                <option value="{{ $g->id_guru }}">{{ $g->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="jadwal[${newRowIndex}][hari]" class="form-select jadwal-hari" required>
                            <option value="">-- Pilih Hari --</option>
                            @foreach ($schoolDays as $day)
                                <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="jadwal[${newRowIndex}][session]" class="form-select jadwal-session" required>
                            <option value="">-- Pilih Sesi --</option>
                            @foreach ($timeSlots as $index => $slot)
                                <option value="{{ $index + 1 }}">{{ $slot['label'] }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger btn-delete-row">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $('#jadwalRows').append(newRow);
            initializeSelect2ForRow(newRowIndex);
            
            // Enable delete button for first row if there are more than one row
            if (rowCount === 1) {
                $('.btn-delete-row').prop('disabled', false);
            }
        });
        
        // Delete row
        $(document).on('click', '.btn-delete-row', function() {
            const rowCount = $('#jadwalRows tr').length;
            
            if (rowCount > 1) {
                $(this).closest('tr').remove();
                
                // Renumber rows
                $('#jadwalRows tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
                
                // Disable delete button for first row if there's only one row left
                if (rowCount === 2) {
                    $('.btn-delete-row').prop('disabled', true);
                }
            }
        });
        
        // Form submit
        $('#formJadwalMassal').on('submit', function(e) {
            e.preventDefault();
            
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Prepare form data
            const formData = $(this).serializeArray();
            
            // Send request
            $.ajax({
                url: '{{ route("jadwal-pelajaran.store-bulk") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '{{ route("jadwal-pelajaran.index") }}';
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat menyimpan jadwal';
                    
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors && Array.isArray(xhr.responseJSON.errors)) {
                            errorMessage = xhr.responseJSON.errors.join('<br>');
                        } else if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                    }
                    
                    Swal.fire({
                        title: 'Gagal!',
                        html: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
        
        // Initialize select2 for a specific row
        function initializeSelect2ForRow(rowIndex) {
            $(`#jadwalRows tr:eq(${rowIndex}) .jadwal-kelas, 
               #jadwalRows tr:eq(${rowIndex}) .jadwal-mapel, 
               #jadwalRows tr:eq(${rowIndex}) .jadwal-guru, 
               #jadwalRows tr:eq(${rowIndex}) .jadwal-hari,
               #jadwalRows tr:eq(${rowIndex}) .jadwal-session`).select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
    });
</script>
@endsection
