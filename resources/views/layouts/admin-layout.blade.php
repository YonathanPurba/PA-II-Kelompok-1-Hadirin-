<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/guru_page.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    @include('layouts.sidebar')

    <div class="content">
        @yield('content')
    </div>

    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const barCtx = document.getElementById('barChartAbsensiBulan').getContext('2d');

        const barChartAbsensiBulan = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(
                    $absensiPerHari->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->translatedFormat('d M')),
                ) !!},
                datasets: [{
                        label: 'Hadir',
                        backgroundColor: '#007bff',
                        data: {!! json_encode($absensiPerHari->pluck('hadir')) !!}
                    },
                    {
                        label: 'Alpa',
                        backgroundColor: '#dc3545',
                        data: {!! json_encode($absensiPerHari->pluck('alpa')) !!}
                    },
                    {
                        label: 'Sakit',
                        backgroundColor: '#fd7e14',
                        data: {!! json_encode($absensiPerHari->pluck('sakit')) !!}
                    },
                    {
                        label: 'Izin',
                        backgroundColor: '#20c997',
                        data: {!! json_encode($absensiPerHari->pluck('izin')) !!}
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
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
                        // Tambahkan ini:
                        categoryPercentage: 0.5, // Lebih kecil = jarak antar batang makin besar
                        barPercentage: 0.7 // Lebih kecil = batang lebih ramping
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
    </script>

</body>

</html>
