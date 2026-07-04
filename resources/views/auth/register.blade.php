<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi — Grosir Sembako</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e293b 0%, #3b82f6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 0;
        }
        .register-card {
            width: 100%;
            max-width: 480px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .register-header {
            background: #1e293b;
            color: #fff;
            padding: 28px 32px;
            text-align: center;
        }
        .register-body {
            background: #fff;
            padding: 32px;
        }
        .btn-register {
            background: #3b82f6;
            color: #fff;
            border: none;
            padding: 10px;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            font-size: 1rem;
        }
        .btn-register:hover { background: #2563eb; color: #fff; }
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

<div class="register-card">
    <div class="register-header">
        <div style="font-size:2.5rem">🛒</div>
        <h5 class="fw-bold mb-1">Daftar Akun Konsumen</h5>
        <p class="mb-0 small" style="color:#94a3b8">Grosir Sembako</p>
    </div>

    <div class="register-body">

        @if($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="nama_konsumen" class="form-control"
                           placeholder="Nama lengkap" value="{{ old('nama_konsumen') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">No. HP</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    <input type="text" name="no_hp" class="form-control"
                           placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                    <textarea name="alamat" class="form-control" rows="2"
                              placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                    <input type="text" name="username" class="form-control"
                           placeholder="Username untuk login" value="{{ old('username') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="pass1"
                           class="form-control" placeholder="Minimal 6 karakter" required>
                    <button type="button" class="input-group-text"
                            onclick="togglePass('pass1','eye1')" style="cursor:pointer">
                        <i class="bi bi-eye" id="eye1"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" id="pass2"
                           class="form-control" placeholder="Ulangi password" required>
                    <button type="button" class="input-group-text"
                            onclick="togglePass('pass2','eye2')" style="cursor:pointer">
                        <i class="bi bi-eye" id="eye2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-register">
                <i class="bi bi-person-plus me-1"></i> Daftar Sekarang
            </button>

            <div class="text-center mt-3">
                <span class="text-muted small">Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="small fw-semibold">Login di sini</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePass(inputId, iconId) {
        var input = document.getElementById(inputId);
        var icon  = document.getElementById(iconId);
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