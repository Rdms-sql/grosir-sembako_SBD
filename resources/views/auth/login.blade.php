<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Grosir Sembako</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e293b 0%, #3b82f6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .login-header {
            background: #1e293b;
            color: #fff;
            padding: 32px;
            text-align: center;
        }
        .login-header .icon {
            font-size: 3rem;
            margin-bottom: 8px;
        }
        .login-body {
            background: #fff;
            padding: 32px;
        }
        .btn-login {
            background: #3b82f6;
            color: #fff;
            border: none;
            padding: 10px;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            font-size: 1rem;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background: #2563eb;
            color: #fff;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }
        .input-group-text {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #64748b;
        }
    </style>
</head>
<body>

<div class="login-card">
    {{-- Header --}}
    <div class="login-header">
        <div class="icon">🛒</div>
        <h5 class="fw-bold mb-1">Grosir Sembako</h5>
        <p class="text-slate-400 mb-0 small" style="color:#94a3b8">
            Silakan login untuk melanjutkan
        </p>
    </div>

    {{-- Body --}}
    <div class="login-body">

        {{-- Alert error --}}
        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            {{-- Username --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input
                        type="text"
                        name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        placeholder="Masukkan username"
                        value="{{ old('username') }}"
                        autofocus
                        required
                    >
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input
                        type="password"
                        name="password"
                        id="passwordInput"
                        class="form-control"
                        placeholder="Masukkan password"
                        required
                    >
                    <button type="button" class="input-group-text"
                            onclick="togglePassword()" style="cursor:pointer">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            {{-- Remember me --}}
            <div class="mb-4 d-flex align-items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="form-check-input mt-0">
                <label for="remember" class="form-check-label text-muted small">
                    Ingat saya
                </label>
            </div>
            <div class="text-center mt-3">
                <span class="text-muted small">Belum punya akun konsumen?</span><br>
                <a href="{{ route('register') }}" class="fw-semibold">Daftar sebagai Konsumen</a>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
</body>
</html>