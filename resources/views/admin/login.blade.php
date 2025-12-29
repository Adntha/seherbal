<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | SeHerbal</title>
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
</head>
<body>
    <div class="logo">
        <a class="logo-se">Se</a><a class="logo-herbal">Herbal</a>
    </div>
    <div class="login-container">
        <header class="login-header">
            <h1>Admin Portal SeHerbal</h1>
            <p>Silakan masuk untuk mengelola konten dan ensiklopedia tanaman.</p>
        </header>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('/admin/login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="admin@seherbal.com" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <div style="text-align: center; margin-top: 25px;">
            <a href="/" style="color: #2e7d32; text-decoration: none; font-size: 14px; font-weight: 500;">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </div>
        <!-- Decorative Leaves -->
    <div class="decorative-leaves">
        <img src="{{ asset('images/leaf-large.png') }}" alt="" class="leaf-decoration leaf-top-right">
        <img src="{{ asset('images/leaf-small.png') }}" alt="" class="leaf-decoration leaf-top-small">
        <img src="{{ asset('images/leaf-large.png') }}" alt="" class="leaf-decoration leaf-bottom-left">
        <img src="{{ asset('images/leaf-small.png') }}" alt="" class="leaf-decoration leaf-bottom-center">
        <img src="{{ asset('images/leaf-small.png') }}" alt="" class="leaf-decoration leaf-bottom-right">
    </div>

    {{-- Script untuk simpan token ke localStorage --}}
    @if(session('admin_token'))
    <script>
        // Simpan token dari session ke localStorage
        localStorage.setItem('admin_token', '{{ session('admin_token') }}');
        console.log('Token saved to localStorage');
    </script>
    @endif

</body>
</html>