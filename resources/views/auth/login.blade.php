<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Bimbingan TA UNEJ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary-gradient-1: #667eea;
            --primary-gradient-2: #764ba2;
            --secondary-gradient-1: #f093fb;
            --secondary-gradient-2: #4facfe;
            --accent-color: #00d4ff;
            --success-color: #00c853;
            --warning-color: #ffc107;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #667eea 100%);
            background-size: 400% 400%;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            position: relative;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 20%, rgba(0,0,0,0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
        }

        .login-container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 80px rgba(0,0,0,0.25), 0 0 60px rgba(102, 126, 234, 0.2);
            overflow: hidden;
            max-width: 950px;
            width: 100%;
            animation: slideUp 0.8s ease-out;
            border: 1px solid rgba(255,255,255,0.3);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(240, 147, 251, 0.2);
            border-radius: 50%;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 250px;
            height: 250px;
            background: rgba(79, 172, 254, 0.15);
            border-radius: 50%;
        }

        .login-header i {
            font-size: 4.5rem;
            margin-bottom: 20px;
            display: block;
            animation: float 3s ease-in-out infinite;
            position: relative;
            z-index: 2;
            text-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .login-header h3 {
            font-weight: 800;
            font-size: 1.8rem;
            margin-bottom: 5px;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .login-header p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.95;
            position: relative;
            z-index: 2;
        }

        .login-form {
            padding: 50px 45px;
            animation: fadeInLeft 0.8s ease-out 0.2s both;
        }

        .login-form h4 {
            color: #333;
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 0.3rem rgba(102, 126, 234, 0.15);
            outline: none;
        }

        .form-control::placeholder {
            color: #999;
        }

        .form-check {
            margin-bottom: 20px;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.3s;
            accent-color: #667eea;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-label {
            cursor: pointer;
            color: #666;
            font-weight: 500;
            user-select: none;
            margin-left: 8px;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px 30px;
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
            color: white;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }

        .register-link small {
            color: #666;
        }

        .register-link a {
            color: #667eea;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .register-link a:hover {
            color: #764ba2;
            transform: translateX(5px);
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(0, 200, 83, 0.15), rgba(0, 200, 83, 0.05));
            color: #00c853;
            border-left: 4px solid #00c853;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(255, 69, 0, 0.15), rgba(255, 69, 0, 0.05));
            color: #ff4500;
            border-left: 4px solid #ff4500;
        }

        .info-panel {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 50px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            animation: fadeInRight 0.8s ease-out 0.2s both;
        }

        .info-panel::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            animation: float 4s ease-in-out infinite;
        }

        .info-panel::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 250px;
            height: 250px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .info-panel h3 {
            font-weight: 800;
            margin-bottom: 25px;
            font-size: 1.8rem;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .info-panel p {
            margin-bottom: 25px;
            font-size: 0.95rem;
            position: relative;
            z-index: 2;
            line-height: 1.6;
        }

        .info-panel ul {
            list-style: none;
            padding: 0;
            position: relative;
            z-index: 2;
        }

        .info-panel li {
            padding: 15px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s;
        }

        .info-panel li:last-child {
            border-bottom: none;
        }

        .info-panel li:hover {
            transform: translateX(10px);
        }

        .info-panel i {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .info-footer {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(255,255,255,0.3);
            position: relative;
            z-index: 2;
        }

        .info-footer small {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .login-container {
                margin: 20px;
                border-radius: 15px;
            }

            .login-form {
                padding: 35px 25px;
            }

            .login-header {
                padding: 35px 25px;
            }

            .login-header i {
                font-size: 3.5rem;
            }

            .login-form h4 {
                font-size: 1.2rem;
            }

            .info-panel {
                padding: 35px 25px;
                order: -1;
            }

            .row {
                flex-direction: column-reverse;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="login-container">
                    <div class="row g-0">
                        <!-- Form Login -->
                        <div class="col-md-6">
                            <div class="login-header">
                                <i class="bi bi-mortarboard-fill"></i>
                                <h3>Sistem Bimbingan TA</h3>
                                <p>Universitas Jember</p>
                            </div>

                            <div class="login-form">
                                <h4>
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    Masuk ke Sistem
                                </h4>

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <strong>Berhasil!</strong> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                        <strong>Gagal!</strong> {{ $errors->first() }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}" id="loginForm">
                                    @csrf

                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="bi bi-envelope-fill"></i> Email
                                        </label>
                                        <input type="email"
                                               name="email"
                                               class="form-control"
                                               value="{{ old('email') }}"
                                               required
                                               autofocus
                                               placeholder="nama@example.com">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="bi bi-lock-fill"></i> Password
                                        </label>
                                        <input type="password"
                                               name="password"
                                               class="form-control"
                                               required
                                               placeholder="••••••••">
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                        <label class="form-check-label" for="remember">
                                            <i class="bi bi-bookmark-heart"></i> Ingat Saya
                                        </label>
                                    </div>

                                    <button type="submit" class="btn btn-login">
                                        <i class="bi bi-arrow-right-circle-fill"></i>
                                        Masuk Sekarang
                                    </button>
                                </form>

                                <div class="register-link">
                                    <small>
                                        Belum punya akun?
                                        <a href="{{ route('register') }}">
                                            <i class="bi bi-person-plus"></i>
                                            Daftar di sini
                                        </a>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Info Panel -->
                        <div class="col-md-6">
                            <div class="info-panel">
                                <h3>
                                    <i class="bi bi-hand-thumbs-up-fill"></i>
                                    Selamat Datang!
                                </h3>
                                <p>Sistem Manajemen Bimbingan Tugas Akhir untuk memudahkan proses akademik Anda.</p>

                                <ul>
                                    <li>
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                        <span>Upload & kelola dokumen bimbingan digital</span>
                                    </li>
                                    <li>
                                        <i class="bi bi-chat-left-dots-fill"></i>
                                        <span>Komunikasi real-time dengan dosen pembimbing</span>
                                    </li>
                                    <li>
                                        <i class="bi bi-graph-up"></i>
                                        <span>Monitor progres bimbingan secara terukur</span>
                                    </li>
                                    <li>
                                        <i class="bi bi-archive-fill"></i>
                                        <span>Arsip lengkap riwayat bimbingan Anda</span>
                                    </li>
                                </ul>

                                <div class="info-footer">
                                    <small>
                                        <i class="bi bi-shield-lock"></i>
                                        Akun Anda aman dan terlindungi dengan enkripsi tingkat tinggi
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
    <script>
        // Tambahkan animasi submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = this.querySelector('.btn-login');
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
            btn.disabled = true;
        });

        // Smooth focus effect
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
