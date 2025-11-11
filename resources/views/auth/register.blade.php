@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ __('Daftar Akun Baru') }}</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Email <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">Tipe Akun <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required onchange="toggleNimNip()">
                                    <option value="">-- Pilih Tipe Akun --</option>
                                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- NIM/NIP -->
                        <div class="row mb-3" id="nim-nip-field" style="display: none;">
                            <label for="nim_nip" class="col-md-4 col-form-label text-md-end">NIM / NIP</label>
                            <div class="col-md-6">
                                <input id="nim_nip" type="text" class="form-control @error('nim_nip') is-invalid @enderror" name="nim_nip" value="{{ old('nim_nip') }}">
                                @error('nim_nip')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Nomor Induk Mahasiswa atau Nomor Induk Pegawai</small>
                            </div>
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">Nomor Telepon</label>
                            <div class="col-md-6">
                                <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="628xxxxxxxxxx">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Password <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Minimal 6 karakter</small>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus"></i> Daftar
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-secondary">
                                    Sudah punya akun? Masuk
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleNimNip() {
    const role = document.getElementById('role').value;
    const nimNipField = document.getElementById('nim-nip-field');

    if (role === 'mahasiswa' || role === 'dosen') {
        nimNipField.style.display = 'flex';
    } else {
        nimNipField.style.display = 'none';
    }
}

// Jalankan saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    toggleNimNip();
});
</script>
@endsection
