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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght@400;700&display=swap">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-minimal.css" rel="stylesheet"> --}}

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />

    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            margin-bottom: 0;
            font-weight: 600;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .table th {
            background-color: #f8f9fa;
        }
        
        .loading-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-text {
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
    @yield('css')
</head>

<body>
    @include('layouts.sidebar')

    <!-- Loading Spinner -->
    <div id="loading" class="loading-container">
        <div class="spinner"></div>
        <div class="loading-text">Memuat halaman...</div>
    </div>


    <div id="flash-data" data-success="{{ session('success') }}" data-error="{{ session('error') }}">
    </div>

    <div class="content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>

    <!-- DataTables CSS and JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

    <!-- Global DataTables Configuration -->
    <script>
        // Global DataTables configuration
        $.extend(true, $.fn.dataTable.defaults, {
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json',
            },
            // Enable pagination by default
            "paging": true,
            // Show entry length menu
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            // Enable info display
            "info": true,
            // Enable searching
            "searching": true,
            // Add DOM positioning for length, filter, etc.
            "dom": '<"top"<"d-flex justify-content-between"<"dataTables_length"l><"dataTables_filter"f>>>rt<"bottom"<"d-flex justify-content-between"<"dataTables_info"i><"dataTables_paginate"p>>><"clear">'
        });
    </script>

    <!-- Global CSS for DataTables -->
    <style>
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 15px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.3em 0.8em;
            border: 1px solid #ddd;
            margin-left: 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #198754;
            color: white !important;
            border-color: #198754;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
            background-color: #f0f0f0;
        }
        .dataTables_wrapper .dataTables_info {
            margin-top: 15px;
        }
        .dt-buttons {
            margin-bottom: 15px;
        }
        
        /* Form styling improvements */
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-weight: 500;
        }
        .form-control:focus, .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        /* Card styling for forms */
        .card-form {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
        .card-form .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        .card-form .card-body {
            padding: 1.5rem;
        }
        
        /* Button styling */
        .btn-action {
            border-radius: 0.375rem;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        .btn-submit {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-submit:hover {
            background-color: #157347;
            border-color: #146c43;
        }
        .btn-cancel {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-cancel:hover {
            background-color: #5c636a;
            border-color: #565e64;
        }
    </style>

    @yield('scripts')

</body>

</html>
