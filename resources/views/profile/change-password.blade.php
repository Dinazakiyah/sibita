@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-lock"></i> Ubah Password
                    </h2>
                    <p class="text-muted mb-0">Perbarui password akun Anda untuk keamanan lebih baik</p>
                </div>
                <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

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

    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-unej text-white">
                    <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Keamanan Password</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Password Saat Ini -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-bold">
                                Password Saat Ini <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                <input type="password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password"
                                       name="current_password"
                                       placeholder="Masukkan password saat ini"
                                       required>
                                @error('current_password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-text text-muted mt-1">
                                <i class="bi bi-info-circle"></i>
                                Kami memerlukan password saat ini untuk memverifikasi identitas Anda
                            </small>
                        </div>

                        <!-- Password Baru -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">
                                Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Masukkan password baru"
                                       required>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-text text-muted mt-1">
                                <i class="bi bi-info-circle"></i>
                                Minimal 6 karakter
                            </small>
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">
                                Konfirmasi Password Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="Konfirmasi password baru"
                                       required>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-check-circle"></i> Ubah Password
                            </button>
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Tips -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-shield-exclamation"></i> Tips Keamanan Password</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Buat Password yang Kuat:</h6>
                    <div class="checklist mb-4">
                        <div class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            <span>Panjang minimal 8 karakter</span>
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            <span>Mengandung huruf besar (A-Z)</span>
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            <span>Mengandung huruf kecil (a-z)</span>
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            <span>Mengandung angka (0-9)</span>
                        </div>
                        <div class="mb-0">
                            <i class="bi bi-check-circle text-success"></i>
                            <span>Mengandung simbol khusus (!@#$%)</span>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Hindari:</h6>
                    <div class="mb-3">
                        <i class="bi bi-x-circle text-danger"></i>
                        <span>Tanggal lahir atau data pribadi</span>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-x-circle text-danger"></i>
                        <span>Nama pengguna atau email</span>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-x-circle text-danger"></i>
                        <span>Password yang terlalu mudah (123456, abcdef)</span>
                    </div>
                    <div>
                        <i class="bi bi-x-circle text-danger"></i>
                        <span>Membagikan password ke siapapun</span>
                    </div>
                </div>
            </div>

            <!-- Additional Security -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-shield-check"></i> Keamanan Tambahan</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3 pb-3 border-bottom">
                            <strong>Perubahan Password Rutin</strong>
                            <p class="mb-0 text-muted small">Ubah password minimal 3 bulan sekali</p>
                        </li>
                        <li class="mb-3 pb-3 border-bottom">
                            <strong>Jangan Gunakan WiFi Publik</strong>
                            <p class="mb-0 text-muted small">Hindari mengubah password di WiFi publik</p>
                        </li>
                        <li>
                            <strong>Log Out Setelah Selesai</strong>
                            <p class="mb-0 text-muted small">Selalu logout saat selesai menggunakan sistem</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-unej {
        background: linear-gradient(135deg, #DC143C 0%, #FFD700 50%, #228B22 100%) !important;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 1.5px solid #e0e0e0;
        color: #DC143C;
    }

    .form-control:focus {
        border-color: #DC143C;
        box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.15);
    }

    .checklist i {
        margin-right: 8px;
    }
</style>
@endsection
