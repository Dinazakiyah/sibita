<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Bimbingan TA UNEJ</title>

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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }

        .register-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }

        .register-header {
            background: linear-gradient(135deg, var(--unej-red), var(--unej-yellow));
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .register-header i {
            font-size: 3.5rem;
            margin-bottom: 15px;
            display: block;
        }

        .register-header h2 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .register-header p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .register-form {
            padding: 40px;
        }

        .form-section-title {
            color: var(--unej-red);
            font-weight: 700;
            font-size: 0.95rem;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--unej-yellow);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-section-title:first-child {
            margin-top: 0;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid #e0e0e0;
            padding: 11px 15px;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--unej-red);
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.15);
            background-color: #fff8f9;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-text {
            font-size: 0.8rem;
            margin-top: 5px;
            color: #6c757d;
        }

        .required {
            color: var(--unej-red);
            font-weight: 600;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--unej-red), var(--unej-green));
            color: white;
            border: none;
            padding: 12px 35px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 1rem;
            width: 100%;
            margin-top: 10px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 20, 60, 0.3);
            color: white;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1.5px solid #e0e0e0;
            color: var(--unej-red);
        }

        .input-group > .form-control:focus {
            border-color: var(--unej-red);
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .info-box {
            background: linear-gradient(135deg, var(--unej-green), var(--unej-yellow));
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-top: 25px;
        }

        .info-box i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .back-to-login a {
            color: var(--unej-red);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-to-login a:hover {
            color: var(--unej-green);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .register-header {
                padding: 30px 20px;
            }

            .register-header i {
                font-size: 2.5rem;
            }

            .register-form {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="register-container">
                    <!-- Header -->
                    <div class="register-header">
                        <i class="bi bi-person-plus-fill"></i>
                        <h2>Daftar Akun Baru</h2>
                        <p>Sistem Bimbingan Tugas Akhir - Universitas Jember</p>
                    </div>

                    <!-- Form Body -->
                    <div class="register-form">
                        <!-- Alert Messages -->
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
                                <strong>Perhatian!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" novalidate>
                            @csrf

                            <!-- Data Pribadi -->
                            <div class="form-section-title">
                                <i class="bi bi-person"></i> Data Pribadi
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        Nama Lengkap <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name') }}"
                                               placeholder="Nama lengkap Anda" required autofocus>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        Email <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email') }}"
                                               placeholder="email@example.com" required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        Nomor Telepon
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                               id="phone" name="phone" value="{{ old('phone') }}"
                                               placeholder="628xxxxxxxxxx">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">
                                        Tipe Akun <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                        <select class="form-select @error('role') is-invalid @enderror"
                                                id="role" name="role" required onchange="toggleNimNip()">
                                            <option value="">-- Pilih Tipe Akun --</option>
                                            <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>
                                                <i class="bi bi-book"></i> Mahasiswa
                                            </option>
                                            <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>
                                                <i class="bi bi-briefcase"></i> Dosen
                                            </option>
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                                <i class="bi bi-gear"></i> Admin
                                            </option>
                                        </select>
                                        @error('role')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- NIM/NIP Field -->
                            <div id="nim-nip-field" style="display: none;">
                                <div class="mb-3">
                                    <label for="nim_nip" class="form-label">
                                        NIM / NIP
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                        <input type="text" class="form-control @error('nim_nip') is-invalid @enderror"
                                               id="nim_nip" name="nim_nip" value="{{ old('nim_nip') }}"
                                               placeholder="Masukkan NIM atau NIP Anda">
                                        @error('nim_nip')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> Nomor Induk Mahasiswa atau Nomor Induk Pegawai
                                    </div>
                                </div>
                            </div>

                            <!-- Keamanan -->
                            <div class="form-section-title">
                                <i class="bi bi-lock"></i> Keamanan
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        Password <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password" placeholder="••••••••" required>
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> Minimal 6 karakter
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password-confirm" class="form-label">
                                        Konfirmasi Password <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                        <input type="password" class="form-control"
                                               id="password-confirm" name="password_confirmation"
                                               placeholder="••••••••" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="info-box">
                                <i class="bi bi-shield-check"></i>
                                <strong>Privasi & Keamanan</strong>
                                <p class="mb-0 mt-2" style="font-size: 0.9rem;">
                                    Data Anda akan disimpan dengan aman dan tidak akan dibagikan kepada pihak ketiga tanpa persetujuan.
                                </p>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-register">
                                    <i class="bi bi-person-plus"></i> Daftar Sekarang
                                </button>
                            </div>

                            <!-- Back to Login -->
                            <div class="back-to-login">
                                <small class="text-muted">
                                    Sudah punya akun?
                                    <a href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right"></i> Masuk di sini
                                    </a>
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleNimNip() {
            const role = document.getElementById('role').value;
            const nimNipField = document.getElementById('nim-nip-field');

            if (role === 'mahasiswa' || role === 'dosen') {
                nimNipField.style.display = 'block';
            } else {
                nimNipField.style.display = 'none';
            }
        }

        // Jalankan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            toggleNimNip();
        });
    </script>
</body>
</html>
