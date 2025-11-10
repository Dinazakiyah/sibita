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
            background: linear-gradient(135deg, var(--unej-red) 0%, var(--unej-yellow) 50%, var(--unej-green) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(135deg, var(--unej-red), var(--unej-yellow));
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-header i {
            font-size: 4rem;
            margin-bottom: 15px;
        }

        .login-form {
            padding: 40px;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--unej-red), var(--unej-green));
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: transform 0.3s;
        }

        .btn-login:hover {
            transform: scale(1.05);
            color: white;
        }

        .form-control:focus {
            border-color: var(--unej-red);
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
        }

        .info-panel {
            background: linear-gradient(135deg, var(--unej-green), var(--unej-yellow));
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-panel h3 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .info-panel ul {
            list-style: none;
            padding: 0;
        }

        .info-panel li {
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }

        .info-panel li:last-child {
            border-bottom: none;
        }

        .info-panel i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="login-container">
                    <div class="row g-0">
                        <!-- Form Login -->
                        <div class="col-md-6">
                            <div class="login-header">
                                <i class="bi bi-mortarboard-fill"></i>
                                <h3>Sistem Bimbingan TA</h3>
                                <p class="mb-0">Universitas Jember</p>
                            </div>

                            <div class="login-form">
                                <h4 class="mb-4 text-center">Login ke Sistem</h4>

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

                                    <div class="mb-3 form-check">
                                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                        <label class="form-check-label" for="remember">
                                            Ingat Saya
                                        </label>
                                    </div>

                                    <button type="submit" class="btn btn-login w-100">
                                        <i class="bi bi-box-arrow-in-right"></i>
                                        Masuk
                                    </button>
                                </form>

                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        Belum punya akun? Hubungi Admin Prodi
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Info Panel -->
                        <div class="col-md-6">
                            <div class="info-panel">
                                <h3>Selamat Datang!</h3>
                                <p>Sistem ini membantu proses bimbingan tugas akhir Anda lebih terstruktur:</p>

                                <ul>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Upload dokumen bimbingan secara digital
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Komunikasi dengan dosen pembimbing
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Tracking progres bimbingan real-time
                                    </li>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        Riwayat bimbingan terdokumentasi
                                    </li>
                                </ul>

                                <div class="mt-4 pt-4 border-top border-white">
                                    <small>
                                        <i class="bi bi-info-circle"></i>
                                        Gunakan email dan password yang telah terdaftar di sistem
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
