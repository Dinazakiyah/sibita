@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-pencil-square"></i> Edit Profil
                    </h2>
                    <p class="text-muted mb-0">Perbarui informasi profil Anda</p>
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
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-unej text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Informasi Pribadi</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- NIM/NIP -->
                        <div class="mb-4">
                            <label for="nim_nip" class="form-label fw-bold">
                                NIM / NIP
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text"
                                       class="form-control @error('nim_nip') is-invalid @enderror"
                                       id="nim_nip"
                                       name="nim_nip"
                                       value="{{ old('nim_nip', $user->nim_nip) }}">
                                @error('nim_nip')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">
                                Nomor Telepon
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                <input type="tel"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="628xxxxxxxxxx">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-info-circle"></i> Informasi Penting
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <small>
                                <i class="bi bi-check-circle text-success"></i>
                                Email harus unik dan belum terdaftar
                            </small>
                        </li>
                        <li class="mb-2">
                            <small>
                                <i class="bi bi-check-circle text-success"></i>
                                Nama akan ditampilkan di seluruh sistem
                            </small>
                        </li>
                        <li class="mb-2">
                            <small>
                                <i class="bi bi-check-circle text-success"></i>
                                Nomor telepon opsional (opsional)
                            </small>
                        </li>
                        <li class="mb-0">
                            <small>
                                <i class="bi bi-check-circle text-success"></i>
                                Untuk mengubah password, kunjungi halaman keamanan
                            </small>
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
</style>
@endsection
