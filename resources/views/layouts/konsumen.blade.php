<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Grosir Sembako</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .navbar-brand { font-weight: 700; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background:#1e293b">
    <div class="container">
        <a class="navbar-brand" href="{{ route('katalog.index') }}">🛒 Grosir Sembako</a>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('katalog.index') }}"
               class="nav-link text-white {{ request()->is('katalog') ? 'fw-bold' : '' }}">
                Katalog
            </a>
            <a href="{{ route('katalog.riwayat') }}"
               class="nav-link text-white {{ request()->is('katalog/riwayat') ? 'fw-bold' : '' }}">
                Pesanan Saya
            </a>
            <span class="text-white-50 small">
                Halo, {{ Auth::user()->nama_lengkap }}!
            </span>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container py-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>