<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Bimbingan TA UNEJ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --unej-red: #DC143C;
            --unej-yellow: #FFD700;
            --unej-green: #228B22;
        }

        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

            /* NEW: agar seluruh halaman fleksibel & bisa center vertikal */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* NEW: wrapper vertikal */
        .page-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }

        .login-header {
            background-color: var(--unej-red);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .login-header i {
            font-size: 4rem;
            margin-bottom: 15px;
        }

        .login-header h3 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .login-header p {
            margin: 0;
            opacity: 0.9;
        }

        .login-form {
            padding: 40px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--unej-red);
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.15);
        }

        .btn-login {
            background-color: var(--unej-red);
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background-color: #b71c1c;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 20, 60, 0.3);
        }

        .form-check-input:checked {
            background-color: var(--unej-red);
            border-color: var(--unej-red);
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .brand-colors {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .brand-colors span {
            width: 30px;
            height: 4px;
            border-radius: 2px;
        }

        .color-red { background-color: var(--unej-red); }
        .color-yellow { background-color: var(--unej-yellow); }
        .color-green { background-color: var(--unej-green); }

        /* NEW: agar footer selalu center di bawah form */
        .footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <i class="bi bi-mortarboard-fill"></i>
            <h3>Sistem Bimbingan TA</h3>
            <p>Universitas Jember</p>
        </div>

        <!-- Form Login -->
        <div class="login-form">
            <h5 class="text-center mb-4 fw-bold">Login ke Sistem</h5>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-envelope"></i> Email
                    </label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           placeholder="nama@example.com">
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-lock"></i> Password
                    </label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           required
                           placeholder="••••••••">
                </div>

                <!-- Remember Me -->
                <div class="mb-4 form-check">
                    <input type="checkbox"
                           name="remember"
                           class="form-check-input"
                           id="remember">
                    <label class="form-check-label" for="remember">
                        Ingat Saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login w-100">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Masuk
                </button>
            </form>

            <!-- Info -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    Belum punya akun? Hubungi Admin Prodi
                </small>
            </div>

            <!-- Brand Colors -->
            <div class="brand-colors">
                <span class="color-red"></span>
                <span class="color-yellow"></span>
                <span class="color-green"></span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <small class="text-muted">
            &copy; {{ date('Y') }} SIBITA by Dinaz.
        </small>
    </div>

</div>

</body>
</html>
