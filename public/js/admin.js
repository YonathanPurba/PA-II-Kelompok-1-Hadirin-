

function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
    document.body.classList.toggle('sidebar-collapsed');
}

const barCanvas = document.getElementById('barChartAbsensiBulan');

if (barCanvas) {
    const labels = JSON.parse(barCanvas.dataset.labels);
    const hadir = JSON.parse(barCanvas.dataset.hadir);
    const alpa = JSON.parse(barCanvas.dataset.alpa);
    const sakit = JSON.parse(barCanvas.dataset.sakit);
    const izin = JSON.parse(barCanvas.dataset.izin);

    const barChartAbsensiBulan = new Chart(barCanvas.getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Hadir',
                    backgroundColor: '#007bff',
                    data: hadir
                },
                {
                    label: 'Alpa',
                    backgroundColor: '#dc3545',
                    data: alpa
                },
                {
                    label: 'Sakit',
                    backgroundColor: '#fd7e14',
                    data: sakit
                },
                {
                    label: 'Izin',
                    backgroundColor: '#20c997',
                    data: izin
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.dataset.label}: ${context.parsed.y}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    },
                    ticks: {
                        maxRotation: 90,
                        minRotation: 45
                    },
                    grid: {
                        display: false
                    },
                    categoryPercentage: 0.5,
                    barPercentage: 0.7
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah'
                    }
                }
            }
        }
    });
}

// $(document).ready(function () {
//     $('#guruTable').DataTable({
//         paging: true,
//         searching: true,
//         ordering: true,
//         responsive: true,
//         language: {
//             search: "Cari:",
//             lengthMenu: "Tampilkan _MENU_ entri",
//             info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
//             infoEmpty: "Tidak ada data tersedia",
//             zeroRecords: "Tidak ditemukan data yang cocok",
//             paginate: {
//                 previous: "Sebelumnya",
//                 next: "Berikutnya"
//             }
//         }
//     });
// });


// $(document).ready(function () {
//     $('#kelasTable').DataTable({
//         paging: true,
//         searching: true,
//         ordering: true,
//         responsive: true,
//         language: {
//             search: "Cari:",
//             lengthMenu: "Tampilkan _MENU_ entri",
//             info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
//             infoEmpty: "Tidak ada data tersedia",
//             zeroRecords: "Tidak ditemukan data yang cocok",
//             paginate: {
//                 previous: "Sebelumnya",
//                 next: "Berikutnya"
//             }
//         }
//     });
// });

// Data Table 
$(document).ready(function () {
    $('#siswaTable, #guruTable, #orangtuaTable, #mataPelajaranTable, #tahunAjaranTable, #kelasTable').DataTable({
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
});

// $(document).ready(function () {
//     $('#orangtuaTable').DataTable({
//         paging: true,
//         searching: true,
//         ordering: true,
//         responsive: true,
//         language: {
//             search: "Cari:",
//             lengthMenu: "Tampilkan _MENU_ entri",
//             info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
//             infoEmpty: "Tidak ada data tersedia",
//             zeroRecords: "Tidak ditemukan data yang cocok",
//             paginate: {
//                 previous: "Sebelumnya",
//                 next: "Berikutnya"
//             }
//         }
//     });
// });

// Tooltip
document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});

// // Modal Guru
// $(document).ready(function () {

//     // Fungsi bantu untuk format waktu dari ISO string ke HH:MM
//     function formatTime(timeStr) {
//         const date = new Date(timeStr);
//         const hours = String(date.getHours()).padStart(2, '0');
//         const minutes = String(date.getMinutes()).padStart(2, '0');
//         return `${hours}:${minutes}`;
//     }

//     $('.btn-view-guru').on('click', function () {
//         const idGuru = $(this).data('id');

//         $.ajax({
//             url: `guru/${idGuru}`,
//             method: 'GET',
//             success: function (res) {
//                 $('#view-nama-lengkap').text(res.nama_lengkap ?? '-');
//                 $('#view-nip').text(res.nip ?? '-');
//                 $('#view-telepon').text(res.nomor_telepon ?? '-');
//                 $('#view-terakhir-login').text(res.user?.last_login ?? '-');

//                 if (res.mata_pelajaran?.length > 0) {
//                     const mapelList = res.mata_pelajaran.map(mp => mp.nama).join(', ');
//                     $('#view-mapel').text(mapelList);
//                 } else {
//                     $('#view-mapel').text('-');
//                 }

//                 const jadwal = res.jadwal ?? [];
//                 $('#view-jadwal').text(jadwal.length);

//                 const tbody = $('#table-jadwal-body');
//                 tbody.empty();

//                 if (jadwal.length > 0) {
//                     // Kelompokkan berdasarkan hari
//                     const grouped = {};
//                     jadwal.forEach(item => {
//                         if (!grouped[item.hari]) grouped[item.hari] = [];
//                         grouped[item.hari].push(item);
//                     });

//                     let rowNumber = 1;
//                     for (const hari in grouped) {
//                         const items = grouped[hari];
//                         items.forEach((item, index) => {
//                             const row = $('<tr>');

//                             const startTime = formatTime(item.waktu_mulai);
//                             const endTime = formatTime(item.waktu_selesai);

//                             row.append(`<td class="text-center">${rowNumber++}</td>`);
//                             row.append(`<td class="text-center">${index === 0 ? hari : ''}</td>`);
//                             row.append(`<td class="text-center">${startTime} - ${endTime}</td>`);
//                             row.append(`<td class="text-center">${item.kelas?.nama_kelas ?? '-'}</td>`);
//                             row.append(`<td class="text-center">${item.mata_pelajaran?.nama ?? '-'}</td>`);

//                             tbody.append(row);
//                         });
//                     }
//                 } else {
//                     tbody.append('<tr><td colspan="5" class="text-center text-muted">Tidak ada jadwal mengajar.</td></tr>');
//                 }

//                 $('#modalViewGuru').modal('show');
//             },
//             error: function () {
//                 $('#view-nama-lengkap, #view-nip, #view-telepon, #view-terakhir-login, #view-mapel, #view-jadwal')
//                     .text('Gagal memuat data.');
//                 $('#table-jadwal-body').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat jadwal.</td></tr>');
//                 $('#modalViewGuru').modal('show');
//             }
//         });
//     });

// });

$(document).ready(function () {
    // Format waktu "HH:mm" sesuai dengan waktu lokal pengguna dan mengurangi satu jam
    function formatTime(timeStr) {
        if (!timeStr) return '-';

        const date = new Date(timeStr); // Parsing ISO string (waktu lokal perangkat)
        
        // Mengurangi satu jam dari waktu
        date.setHours(date.getHours() - 1);
        
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    // Format tanggal dan waktu sesuai dengan zona waktu lokal perangkat pengguna, dan mengurangi satu jam
    function formatDateTime(isoString) {
        if (!isoString) return '-';

        // Parse ISO string ke objek Date
        const date = new Date(isoString);

        // Mengurangi satu jam dari waktu
        date.setHours(date.getHours() - 1);

        // Gunakan toLocaleString untuk menampilkan waktu sesuai zona waktu perangkat pengguna
        const localDate = date.toLocaleString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false, // Gunakan format 24 jam
        });

        return localDate;
    }

    $('.btn-view-guru').on('click', function () {
        const idGuru = $(this).data('id');

        $.ajax({
            url: `guru/${idGuru}`,
            method: 'GET',
            success: function (res) {
                // Info dasar
                $('#view-nama-lengkap').text(res.nama_lengkap || '-');
                $('#view-nip').text(res.nip || '-');
                $('#view-telepon').text(res.nomor_telepon || '-');

                // Last login sesuai dengan waktu lokal perangkat dan dikurangi 1 jam
                $('#view-terakhir-login').text(formatDateTime(res.user?.last_login_at));

                // Mata Pelajaran
                const mapelList = (res.mata_pelajaran ?? []).map(mp => mp.nama).join(', ') || '-';
                $('#view-mapel').text(mapelList);

                // Jadwal
                const jadwal = res.jadwal ?? [];
                $('#view-jadwal').text(jadwal.length);

                const tbody = $('#table-jadwal-body').empty();
                const dayOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

                // Kelompokkan dan susun jadwal
                const grouped = {};
                jadwal.forEach(item => {
                    if (!grouped[item.hari]) grouped[item.hari] = [];
                    grouped[item.hari].push(item);
                });

                let rowNumber = 1;

                if (jadwal.length) {
                    dayOrder.forEach(hari => {
                        if (grouped[hari]) {
                            grouped[hari].sort((a, b) => a.waktu_mulai.localeCompare(b.waktu_mulai));
                            grouped[hari].forEach((item, index) => {
                                tbody.append(`
                                    <tr>
                                        <td class="text-center">${rowNumber++}</td>
                                        <td class="text-center">${index === 0 ? hari : ''}</td>
                                        <td class="text-center">${formatTime(item.waktu_mulai)} - ${formatTime(item.waktu_selesai)}</td>
                                        <td class="text-center">${item.kelas?.nama_kelas || '-'}</td>
                                        <td class="text-center">${item.mata_pelajaran?.nama || '-'}</td>
                                    </tr>
                                `);
                            });
                        }
                    });
                } else {
                    tbody.append('<tr><td colspan="5" class="text-center text-muted">Tidak ada jadwal mengajar.</td></tr>');
                }

                $('#modalViewGuru').modal('show');
            },
            error: function () {
                const fallback = 'Gagal memuat data.';
                $('#view-nama-lengkap, #view-nip, #view-telepon, #view-terakhir-login, #view-mapel, #view-jadwal')
                    .text(fallback);
                $('#table-jadwal-body').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat jadwal.</td></tr>');
                $('#modalViewGuru').modal('show');
            }
        });
    });
});


// Modal Orang Tua 
document.addEventListener('DOMContentLoaded', function () {
    // Ambil semua tombol "Lihat"
    const viewButtons = document.querySelectorAll('.btn-view-orangtua');

    // Setiap kali tombol "Lihat" diklik
    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Ambil data dari data-attributes
            const nama = this.getAttribute('data-nama');
            const alamat = this.getAttribute('data-alamat');
            const pekerjaan = this.getAttribute('data-pekerjaan');
            const nomor = this.getAttribute('data-nomor');
            const anak = this.getAttribute('data-anak');

            // Isi modal dengan data
            document.getElementById('modal-nama').textContent = nama;
            document.getElementById('modal-alamat').textContent = alamat;
            document.getElementById('modal-pekerjaan').textContent = pekerjaan;
            document.getElementById('modal-nomor').textContent = nomor;
            document.getElementById('modal-anak').innerHTML = anak.split(', ').map(anak => `<li>${anak}</li>`).join('');
        });
    });
});

// Modal Siswa
$(document).on('click', '.btn-view-siswa', function () {
    const siswa = $(this).data('siswa');

    $('#viewNama').text(siswa.nama ?? '-');
    $('#viewGender').text(siswa.jenis_kelamin ?? '-');
    $('#viewNisn').text(siswa.nis ?? '-');
    $('#viewAlamat').text(siswa.alamat ?? '-');

    // Jika relasi kelas kosong, hindari error
    const namaKelas = siswa.kelas && siswa.kelas.nama_kelas ? siswa.kelas.nama_kelas : '-';
    $('#viewKelas').text(namaKelas);
});

// Modal Tahun Ajaran
document.addEventListener('DOMContentLoaded', function () {
    const viewButtons = document.querySelectorAll('.btn-view-tahun');

    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            const tahunId = this.getAttribute('data-id');
            const tahunNama = this.getAttribute('data-nama');
            const tahunMulai = this.getAttribute('data-mulai');
            const tahunSelesai = this.getAttribute('data-selesai');
            const status = this.getAttribute('data-status');

            // Masukkan data ke dalam modal
            document.getElementById('view-nama-tahun').innerText = tahunNama;
            document.getElementById('view-tanggal-mulai').innerText = tahunMulai;
            document.getElementById('view-tanggal-selesai').innerText = tahunSelesai;
            document.getElementById('view-status').innerText = status;
        });
    });
});


// Modal Mata Pelajaran 
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalViewMapel');

    document.querySelectorAll('.btn-view-mapel').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;

            // Isi info dasar dari data attribute
            document.getElementById('view-nama').textContent = this.dataset.nama;
            document.getElementById('view-kode').textContent = this.dataset.kode;
            document.getElementById('view-deskripsi').textContent = this.dataset.deskripsi;
            document.getElementById('guruPengampuList').innerHTML = ''; // Kosongkan dulu

            // Ambil jumlah guru (endpoint khusus jumlah saja)
            fetch(`/mata-pelajaran/${id}/jumlah-guru`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('view-jumlah-guru').textContent = data.jumlah_guru + ' guru';
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('view-jumlah-guru').textContent = 'Gagal memuat';
                });

            // Ambil daftar guru pengampu lengkap
            fetch(`/mata-pelajaran/${id}/guru-pengampu`)
                .then(res => res.json())
                .then(data => {
                    const listContainer = document.getElementById('guruPengampuList');
                    if (data.jumlah === 0) {
                        listContainer.innerHTML = '<li class="list-group-item">Belum ada guru pengampu.</li>';
                    } else {
                        data.data.forEach(guru => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item';
                            li.textContent = guru.nama_lengkap;
                            listContainer.appendChild(li);
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('guruPengampuList').innerHTML = '<li class="list-group-item text-danger">Gagal memuat guru.</li>';
                });
        });
    });
});


// Modal Kelas
document.addEventListener('DOMContentLoaded', function () {
    // Event listener untuk tombol lihat kelas
    const btnViewKelas = document.querySelectorAll('.btn-view-kelas');

    btnViewKelas.forEach(button => {
        button.addEventListener('click', function () {
            // Ambil data dari tombol yang diklik
            const idKelas = button.getAttribute('data-id');
            const namaKelas = button.getAttribute('data-nama');
            const tingkat = button.getAttribute('data-tingkat');
            const guru = button.getAttribute('data-guru');

            // Set data ke dalam modal
            document.getElementById('view-nama-kelas').innerText = namaKelas;
            document.getElementById('view-tingkat').innerText = tingkat;
            document.getElementById('view-guru').innerText = guru;

            // Jika modal belum terbuka, buka modal
            const modal = new bootstrap.Modal(document.getElementById('modalViewKelas'));
            modal.show();
        });
    });
});


// Loading Script
window.addEventListener('load', function () {
    const loading = document.getElementById('loading');
    setTimeout(() => {
        loading.style.opacity = 0;
        setTimeout(() => {
            loading.style.display = 'none';
        }, 200); // match dengan durasi transition CSS
    }, 200); // durasi loading bisa disesuaikan
});
