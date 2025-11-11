@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-person-circle"></i> Profil Saya
                    </h2>
                    <p class="text-muted mb-0">Kelola informasi profil dan keamanan akun Anda</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <div class="mb-3">
                        <div class="rounded-circle bg-gradient-unej d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px;">
                            <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                        </div>
                    </div>

                    <!-- Info -->
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">
                        <span class="badge bg-primary">{{ strtoupper($user->role) }}</span>
                    </p>

                    <!-- Details -->
                    <div class="text-start">
                        <div class="mb-3 pb-3 border-bottom">
                            <small class="text-muted"><i class="bi bi-envelope"></i> Email</small>
                            <p class="mb-0 fw-600">{{ $user->email }}</p>
                        </div>

                        @if($user->nim_nip)
                            <div class="mb-3 pb-3 border-bottom">
                                <small class="text-muted"><i class="bi bi-hash"></i> NIM/NIP</small>
                                <p class="mb-0 fw-600">{{ $user->nim_nip }}</p>
                            </div>
                        @endif

                        @if($user->phone)
                            <div class="mb-3 pb-3 border-bottom">
                                <small class="text-muted"><i class="bi bi-telephone"></i> Telepon</small>
                                <p class="mb-0 fw-600">{{ $user->phone }}</p>
                            </div>
                        @endif

                        <div class="mb-0">
                            <small class="text-muted"><i class="bi bi-calendar-event"></i> Bergabung</small>
                            <p class="mb-0 fw-600">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-pencil"></i> Edit Informasi
                    </a>
                    <a href="{{ route('profile.password.edit') }}" class="btn btn-outline-danger w-100">
                        <i class="bi bi-lock"></i> Ubah Password
                    </a>
                </div>
            </div>
        </div>

        <!-- Details Tab -->
        <div class="col-lg-8 mb-4">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-4 border-bottom-0" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-personal" data-bs-toggle="tab" data-bs-target="#panel-personal" type="button" role="tab">
                        <i class="bi bi-person"></i> Informasi Pribadi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-status" data-bs-toggle="tab" data-bs-target="#panel-status" type="button" role="tab">
                        <i class="bi bi-info-circle"></i> Status
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-security" data-bs-toggle="tab" data-bs-target="#panel-security" type="button" role="tab">
                        <i class="bi bi-shield-lock"></i> Keamanan
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Personal Info Tab -->
                <div class="tab-pane fade show active" id="panel-personal" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-unej text-white">
                            <h5 class="mb-0"><i class="bi bi-person"></i> Informasi Pribadi</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="text-muted mb-1"><small>Nama Lengkap</small></p>
                                    <h6 class="fw-bold">{{ $user->name }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1"><small>Email</small></p>
                                    <h6 class="fw-bold">{{ $user->email }}</h6>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="text-muted mb-1"><small>Nomor Telepon</small></p>
                                    <h6 class="fw-bold">{{ $user->phone ?? 'Belum diisi' }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1"><small>NIM / NIP</small></p>
                                    <h6 class="fw-bold">{{ $user->nim_nip ?? 'Belum diisi' }}</h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted mb-1"><small>Tipe Akun</small></p>
                                    <h6 class="fw-bold">
                                        <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1"><small>Bergabung Sejak</small></p>
                                    <h6 class="fw-bold">{{ $user->created_at->format('d M Y H:i') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Tab -->
                <div class="tab-pane fade" id="panel-status" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-unej text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Status Akun</h5>
                        </div>
                        <div class="card-body">
                            @if($user->isMahasiswa() && $statusMahasiswa)
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="info-card p-3 rounded-3 bg-light">
                                            <p class="text-muted mb-1"><small>Status TA</small></p>
                                            <h6 class="fw-bold">{{ ucfirst($statusMahasiswa->status_ta) }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-card p-3 rounded-3 bg-light">
                                            <p class="text-muted mb-1"><small>Total Bimbingan</small></p>
                                            <h6 class="fw-bold">{{ $user->bimbinganAsMahasiswa()->count() }} Sesi</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-card p-3 rounded-3 bg-light">
                                            <p class="text-muted mb-1"><small>Layak Sempro</small></p>
                                            <h6 class="fw-bold">
                                                @if($statusMahasiswa->layak_sempro)
                                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Ya</span>
                                                @else
                                                    <span class="badge bg-warning"><i class="bi bi-x-circle"></i> Belum</span>
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-card p-3 rounded-3 bg-light">
                                            <p class="text-muted mb-1"><small>Layak Sidang</small></p>
                                            <h6 class="fw-bold">
                                                @if($statusMahasiswa->layak_sidang)
                                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Ya</span>
                                                @else
                                                    <span class="badge bg-warning"><i class="bi bi-x-circle"></i> Belum</span>
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-card p-3 rounded-3 bg-light">
                                            <p class="text-muted mb-1"><small>Sudah Sidang</small></p>
                                            <h6 class="fw-bold">
                                                @if($statusMahasiswa->sudah_sidang)
                                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Ya</span>
                                                @else
                                                    <span class="badge bg-warning"><i class="bi bi-x-circle"></i> Belum</span>
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="info-card p-3 rounded-3 bg-light">
                                            <p class="text-muted mb-1"><small>Dosen Pembimbing</small></p>
                                            <h6 class="fw-bold">{{ $user->dosenPembimbing()->count() }} Orang</h6>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-info-circle" style="font-size: 2rem; color: #ccc;"></i>
                                    <p class="text-muted mt-3">Status khusus untuk mahasiswa</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div class="tab-pane fade" id="panel-security" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-unej text-white">
                            <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Keamanan Akun</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-4">
                                <i class="bi bi-info-circle"></i>
                                <strong>Tips Keamanan:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Gunakan password yang kuat (minimal 8 karakter)</li>
                                    <li>Gabungkan huruf besar, huruf kecil, angka, dan simbol</li>
                                    <li>Jangan pernah bagikan password Anda kepada siapapun</li>
                                    <li>Ubah password secara berkala untuk keamanan maksimal</li>
                                </ul>
                            </div>

                            <div class="mb-4 pb-4 border-bottom">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-key"></i> Manajemen Password
                                </h6>
                                <p class="text-muted mb-3">Ubah password akun Anda secara berkala untuk menjaga keamanan.</p>
                                <a href="{{ route('profile.password.edit') }}" class="btn btn-danger">
                                    <i class="bi bi-lock"></i> Ubah Password
                                </a>
                            </div>

                            <div>
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-shield-check"></i> Status Keamanan
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="security-item p-3 rounded-3 bg-light d-flex align-items-center">
                                            <i class="bi bi-shield-fill text-success" style="font-size: 1.5rem;"></i>
                                            <div class="ms-3">
                                                <p class="mb-0 text-muted"><small>Email Terverifikasi</small></p>
                                                <h6 class="fw-bold mb-0">
                                                    @if($user->email_verified_at)
                                                        <span class="badge bg-success">Ya</span>
                                                    @else
                                                        <span class="badge bg-warning">Tidak</span>
                                                    @endif
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="security-item p-3 rounded-3 bg-light d-flex align-items-center">
                                            <i class="bi bi-person-fill-lock text-info" style="font-size: 1.5rem;"></i>
                                            <div class="ms-3">
                                                <p class="mb-0 text-muted"><small>Akun Aktif</small></p>
                                                <h6 class="fw-bold mb-0">
                                                    <span class="badge bg-success">Ya</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-unej {
        background: linear-gradient(135deg, #DC143C 0%, #FFD700 50%, #228B22 100%) !important;
    }

    .fw-600 {
        font-weight: 600;
    }

    .info-card {
        transition: all 0.3s;
    }

    .info-card:hover {
        background-color: #f5f5f5 !important;
        transform: translateY(-2px);
    }

    .nav-tabs .nav-link {
        color: #666;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 10px 15px;
        transition: all 0.3s;
    }

    .nav-tabs .nav-link:hover {
        color: #DC143C;
    }

    .nav-tabs .nav-link.active {
        color: #DC143C;
        background: none;
        border-bottom: 3px solid #DC143C;
    }
</style>
@endsection
