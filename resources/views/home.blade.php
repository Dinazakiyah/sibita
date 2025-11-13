@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-gradient-unej rounded-3 p-5 text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="mb-2">
                            <i class="bi bi-hand-thumbs-up-fill"></i> Selamat datang, {{ auth()->user()->name }}!
                        </h1>
                        <p class="mb-0 opacity-90">
                            <i class="bi bi-calendar-event"></i>
                            {{ date('l, d F Y', strtotime(now())) }}
                        </p>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-light text-dark fs-6 px-3 py-2">
                            <i class="bi bi-shield-check"></i>
                            {{ strtoupper(auth()->user()->role) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content based on Role -->
    <div class="row">
        <!-- Mahasiswa Section -->
        @if(auth()->user()->isMahasiswa())
            <div class="col-lg-8 mb-4">
                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card card-stats border-0 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="stats-icon bg-primary-light rounded-3 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--unej-red), var(--unej-blue));">
                                    <i class="bi bi-file-earmark-text text-white fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total Bimbingan</h6>
                                    <h3 class="mb-0">{{ auth()->user()->bimbinganAsMahasiswa()->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card card-stats border-0 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="stats-icon bg-success-light rounded-3 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--unej-blue), var(--unej-gold));">
                                    <i class="bi bi-check-circle text-white fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Status TA</h6>
                                    <h3 class="mb-0">
                                        @if($statusMahasiswa)
                                            {{ ucfirst($statusMahasiswa->status_ta) }}
                                        @else
                                            <small>Belum ada</small>
                                        @endif
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profil Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-gradient-unej text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-person-badge"></i> Identitas Pribadi Mahasiswa
                        </h5>
                        <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modalIdentitasMahasiswa">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1"><small>Nama Lengkap</small></p>
                                <h6 class="fw-600">{{ auth()->user()->name }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1"><small>NIM</small></p>
                                <h6 class="fw-600">{{ auth()->user()->nim_nip ?? '-' }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1"><small>Email</small></p>
                                <h6 class="fw-600">{{ auth()->user()->email }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1"><small>No. Telepon</small></p>
                                <h6 class="fw-600">{{ auth()->user()->phone ?? '-' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bimbingan -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-unej text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history"></i> Bimbingan Terakhir
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->bimbinganAsMahasiswa()->count() > 0)
                            @foreach(auth()->user()->bimbinganAsMahasiswa()->latest()->take(3)->get() as $bimbingan)
                                <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                                    <div>
                                        <h6 class="mb-1">{{ $bimbingan->judul ?? 'Bimbingan #' . $bimbingan->id }}</h6>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar"></i>
                                            {{ $bimbingan->created_at->format('d M Y') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-info">{{ ucfirst($bimbingan->status) }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">Belum ada bimbingan</p>
                                <a href="{{ route('mahasiswa.bimbingan.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Buat Bimbingan Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Dosen Pembimbing -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-gradient-unej text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-fill-check"></i> Dosen Pembimbing
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->dosenPembimbing()->count() > 0)
                            @foreach(auth()->user()->dosenPembimbing as $dosen)
                                <div class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--unej-red), var(--unej-blue)) !important;">
                                            <i class="bi bi-person-fill text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $dosen->name }}</h6>
                                            <small class="text-muted">{{ $dosen->nim_nip }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-person-dash" style="font-size: 2rem; color: #ccc;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada dosen pembimbing</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-unej text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning-fill"></i> Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('mahasiswa.bimbingan.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-plus-circle"></i> Bimbingan Baru
                        </a>
                        <a href="{{ route('mahasiswa.riwayat.export') }}" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-download"></i> Export Riwayat
                        </a>
                        <button class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#modalIdentitasMahasiswa">
                            <i class="bi bi-person-badge"></i> Lihat Identitas
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Dosen Section -->
        @if(auth()->user()->isDosen())
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-unej text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-people"></i> Mahasiswa Bimbingan
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Data mahasiswa yang Anda bimbing akan ditampilkan di sini.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Admin Section -->
        @if(auth()->user()->isAdmin())
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-unej text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-sliders"></i> Manajemen Sistem
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Fitur manajemen admin akan ditampilkan di sini.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Identitas Mahasiswa -->
@if(auth()->user()->isMahasiswa())
<div class="modal fade" id="modalIdentitasMahasiswa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-unej text-white border-0">
                <h5 class="modal-title">
                    <i class="bi bi-person-badge-fill"></i> Identitas Pribadi Mahasiswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-5">
                <div class="text-center mb-5">
                    <div class="rounded-circle bg-gradient-unej d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                        <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                    </div>
                    <h4>{{ auth()->user()->name }}</h4>
                    <p class="text-muted mb-0">
                        <i class="bi bi-shield-check"></i> Mahasiswa
                    </p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="info-box bg-light p-3 rounded-3">
                            <p class="text-muted mb-1"><small><i class="bi bi-hash"></i> NIM</small></p>
                            <h6 class="fw-bold">{{ auth()->user()->nim_nip ?? 'Belum diisi' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-box bg-light p-3 rounded-3">
                            <p class="text-muted mb-1"><small><i class="bi bi-envelope"></i> Email</small></p>
                            <h6 class="fw-bold">{{ auth()->user()->email }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-box bg-light p-3 rounded-3">
                            <p class="text-muted mb-1"><small><i class="bi bi-telephone"></i> Telepon</small></p>
                            <h6 class="fw-bold">{{ auth()->user()->phone ?? 'Belum diisi' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-box bg-light p-3 rounded-3">
                            <p class="text-muted mb-1"><small><i class="bi bi-calendar-event"></i> Bergabung</small></p>
                            <h6 class="fw-bold">{{ auth()->user()->created_at->format('d M Y') }}</h6>
                        </div>
                    </div>
                </div>

                @if($statusMahasiswa)
                    <hr class="my-4">
                    <h5 class="mb-3"><i class="bi bi-info-circle"></i> Status TA</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-box bg-light p-3 rounded-3">
                                <p class="text-muted mb-1"><small>Status TA</small></p>
                                <h6 class="fw-bold">{{ ucfirst($statusMahasiswa->status_ta) }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box bg-light p-3 rounded-3">
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
                            <div class="info-box bg-light p-3 rounded-3">
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
                            <div class="info-box bg-light p-3 rounded-3">
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
                    </div>
                @endif
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    :root {
        --unej-red: #DC143C;
        --unej-gold: #FFD700;
        --unej-blue: #003DA5;
        --unej-dark: #1a1a1a;
    }

    .bg-gradient-unej {
        background: linear-gradient(135deg, var(--unej-red) 0%, var(--unej-blue) 100%) !important;
    }

    .card-stats {
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #e0e0e0 !important;
    }

    .card-stats:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(220, 20, 60, 0.15) !important;
    }

    .info-box {
        transition: all 0.3s;
        border-left: 4px solid var(--unej-red);
    }

    .info-box:hover {
        background-color: #f0f0f0 !important;
        transform: translateX(5px);
        border-left-color: var(--unej-gold);
    }

    .fw-600 {
        font-weight: 600;
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(0, 200, 83, 0.15), rgba(0, 200, 83, 0.05));
        color: #00c853;
        border-left: 4px solid #00c853;
        border: none;
    }

    .badge {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>
@endsection
