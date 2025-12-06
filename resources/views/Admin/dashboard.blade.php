{{-- File: resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-speedometer2"></i> Dashboard Admin Prodi</h2>
    <p class="text-muted">Selamat datang, {{ auth()->user()->name }}</p>
</div>

<!-- Statistik Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-people-fill" style="font-size: 3rem; color: var(--unej-red);"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ $totalMahasiswa }}</h2>
                <p class="text-muted mb-0">Total Mahasiswa</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-person-badge-fill" style="font-size: 3rem; color: var(--unej-yellow);"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ $totalDosen }}</h2>
                <p class="text-muted mb-0">Total Dosen</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-file-earmark-text-fill" style="font-size: 3rem; color: var(--unej-green);"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ $totalBimbingan }}</h2>
                <p class="text-muted mb-0">Total Bimbingan</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-trophy-fill" style="font-size: 3rem; color: var(--unej-green);"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ $mahasiswaLayakSidang }}</h2>
                <p class="text-muted mb-0">Layak Sidang</p>
            </div>
        </div>
    </div>
</div>

<!-- Progress Overview -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up-arrow"></i> Progress Mahasiswa
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="p-3">
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-secondary" role="progressbar"
                                     style="width: {{ $totalMahasiswa > 0 ? (($totalMahasiswa - $mahasiswaLayakSempro) / $totalMahasiswa * 100) : 0 }}%">
                                </div>
                            </div>
                            <h4 class="fw-bold">{{ $totalMahasiswa - $mahasiswaLayakSempro }}</h4>
                            <p class="text-muted mb-0">Bimbingan Awal</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar" role="progressbar"
                                     style="width: {{ $totalMahasiswa > 0 ? (($mahasiswaLayakSempro - $mahasiswaLayakSidang) / $totalMahasiswa * 100) : 0 }}%; background: var(--unej-yellow)">
                                </div>
                            </div>
                            <h4 class="fw-bold">{{ $mahasiswaLayakSempro - $mahasiswaLayakSidang }}</h4>
                            <p class="text-muted mb-0">Layak Sempro</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ $totalMahasiswa > 0 ? ($mahasiswaLayakSidang / $totalMahasiswa * 100) : 0 }}%">
                                </div>
                            </div>
                            <h4 class="fw-bold">{{ $mahasiswaLayakSidang }}</h4>
                            <p class="text-muted mb-0">Layak Sidang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Aktivitas Bimbingan Terbaru -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history"></i> Aktivitas Bimbingan Terbaru
                </h5>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                @forelse($recentBimbingan as $bimbingan)
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-file-text" style="color: var(--unej-red);"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $bimbingan->mahasiswa->name }}</h6>
                            <p class="mb-1 small text-muted">{{ Str::limit($bimbingan->judul, 50) }}</p>
                            <div class="d-flex align-items-center gap-2">
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i>
                                    {{ $bimbingan->tanggal_upload->diffForHumans() }}
                                </small>
                                <span class="{{ $bimbingan->getStatusBadge() }} badge-sm">
                                    {{ $bimbingan->getStatusText() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">Belum ada aktivitas bimbingan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Status Mahasiswa -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-person-check"></i> Status Mahasiswa Terbaru
                </h5>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                @forelse($mahasiswa as $mhs)
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-1">{{ $mhs->name }}</h6>
                            <small class="text-muted">
                                <i class="bi bi-card-text"></i> {{ $mhs->nim_nip }}
                            </small>
                        </div>
                        <div class="text-end">
                            @if($mhs->statusMahasiswa?->layak_sidang)
                                <span class="badge bg-success mb-1">
                                    <i class="bi bi-trophy-fill"></i> Layak Sidang
                                </span>
                            @elseif($mhs->statusMahasiswa?->layak_sempro)
                                <span class="badge" style="background: var(--unej-yellow); color: #333;">
                                    <i class="bi bi-check-circle-fill"></i> Layak Sempro
                                </span>
                            @else
                                <span class="badge bg-secondary mb-1">
                                    <i class="bi bi-hourglass-split"></i> Bimbingan Awal
                                </span>
                            @endif
                            <div class="progress" style="height: 5px; width: 100px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ $mhs->statusMahasiswa?->getProgresPercentage() ?? 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-people" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">Belum ada mahasiswa terdaftar</p>
                        <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-unej-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
