

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

$(document).ready(function () {
    $('#guruTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
            infoEmpty: "Tidak ada data tersedia",
            zeroRecords: "Tidak ditemukan data yang cocok",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya"
            }
        }
    });
});

$(document).ready(function () {
    $('#kelasTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
            infoEmpty: "Tidak ada data tersedia",
            zeroRecords: "Tidak ditemukan data yang cocok",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya"
            }
        }
    });
});


$(document).ready(function () {
    $('#tabel-guru').DataTable({
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

$(document).ready(function () {
    $('#orangtuaTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        }
    });
});

// Tooltip
document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});

$(document).ready(function () {
    $('.btn-view-guru').on('click', function () {
        const idGuru = $(this).data('id');

        $.ajax({
            url: `guru/${idGuru}`,
            method: 'GET',
            success: function (res) {
                $('#view-nama-lengkap').text(res.nama_lengkap ?? '-');
                $('#view-nip').text(res.nip ?? '-');
                $('#view-telepon').text(res.user?.nomor_telepon ?? '-');
                $('#view-terakhir-login').text(res.user?.last_login ?? '-');

                if (res.mata_pelajaran?.length > 0) {
                    const mapelList = res.mata_pelajaran.map(mp => mp.nama).join(', ');
                    $('#view-mapel').text(mapelList);
                } else {
                    $('#view-mapel').text('-');
                }

                const jadwal = res.jadwal ?? [];
                $('#view-jadwal').text(jadwal.length);

                const tbody = $('#table-jadwal-body');
                tbody.empty();

                if (jadwal.length > 0) {
                    // Kelompokkan berdasarkan hari
                    const grouped = {};
                    jadwal.forEach(item => {
                        if (!grouped[item.hari]) grouped[item.hari] = [];
                        grouped[item.hari].push(item);
                    });

                    let rowNumber = 1;
                    for (const hari in grouped) {
                        const items = grouped[hari];
                        items.forEach((item, index) => {
                            const row = $('<tr>');
                            row.append(`<td class="text-center">${rowNumber++}</td>`);
                            row.append(`<td class="text-center">${index === 0 ? hari : ''}</td>`);
                            row.append(`<td class="text-center">${item.waktu_mulai} - ${item.waktu_selesai}</td>`);
                            row.append(`<td class="text-center">${item.kelas?.nama_kelas ?? '-'}</td>`);
                            row.append(`<td class="text-center">${item.mata_pelajaran?.nama ?? '-'}</td>`);
                            tbody.append(row);
                        });
                    }
                } else {
                    tbody.append('<tr><td colspan="5" class="text-center text-muted">Tidak ada jadwal mengajar.</td></tr>');
                }

                $('#modalViewGuru').modal('show');
            },
            error: function () {
                $('#view-nama-lengkap, #view-nip, #view-telepon, #view-terakhir-login, #view-mapel, #view-jadwal')
                    .text('Gagal memuat data.');
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
