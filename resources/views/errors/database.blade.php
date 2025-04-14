<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kesalahan Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fdfdfd;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-container {
            padding-top: 100px;
            text-align: center;
        }

        .error-title {
            font-size: 48px;
            color: #dc3545;
        }

        .error-message {
            font-size: 18px;
            margin-top: 10px;
            color: #6c757d;
        }

        .btn-home {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container error-container">
        <h1 class="error-title">Oops! Ada yang salah.</h1>
        <p class="error-message">Kami mengalami kendala dalam mengakses database.</p>
        <p class="error-message">Silakan coba beberapa saat lagi atau hubungi administrator sistem.</p>
        <a href="{{ url('/') }}" class="btn btn-primary btn-home">Kembali ke Beranda</a>
    </div>
</body>

</html>
