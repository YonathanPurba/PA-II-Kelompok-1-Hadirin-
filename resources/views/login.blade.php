<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HadirIn - Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="container">
        <div class="login">
            <div class="label_login">
                <img class="logo_image" src="{{ asset('images/HadirIn.jpg') }}" alt="gambar">
                <h1 style="font-family: 'pacifico', cursive;">Click to Know Once</h1>
                <div style="text-align: center">
                    <h1>Login</h1>
                    <p>Masukkan Informasi Detail Anda</p>
                </div>
                <!-- Form login -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <label>Username :</label><br>
                    <input type="text" name="username" placeholder="Masukkan username Anda" required><br>

                    <label>Password :</label><br>
                    <input type="password" name="password" placeholder="********" required><br>                
            </div>

            <div class="check">
                <div class="checkbox">
                    <label><input type="checkbox" id="remember">Ingatkan Saya</label>
                    <a href="#">Lupa Password?</a>
                </div>
            </div>
            <div class="login_or_reset">
                <button type="submit">Log In</button>
            </div>
            </form>

            <!-- Menampilkan pesan error jika login gagal -->
            @if (session('error'))
                <p style="color: red;">{{ session('error') }}</p>
            @endif
        </div>

        {{-- <div class="logo">
            <img src="{{ asset('images/login_web.jpg') }}" alt="">
        </div> --}}
    </div>
</body>

</html>
