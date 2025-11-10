{{-- File: resources/views/dosen/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-person-workspace"></i> Dashboard Dosen Pembimbing</h2>
    <p class="text-muted">Selamat datang, {{ auth()->user()->name }}</p>
</div>

<!-- Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card text-center h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-people" style="font-size: 3rem; color: var(--unej-red);"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ $totalMahasiswa }}</h2>
                <p class="text-muted mb-0">Mahasiswa Bimbingan</p>
                <a href="{{ route('dosen.mahasiswa.index') }}" class="btn btn-sm btn-unej-primary mt-2">
                    <i class="bi bi-eye"></i> Lihat Semua
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-center h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-clock-history" style="font-size: 3rem; color: var(--unej-yellow);"></i>
                </div>
                <h2 class="fw-bold mb-1">{{ $pendingReview }}</h2>
                <p class="text-muted mb-0">Menunggu Review</p>
                @if($pendingReview > 0)
                    <span class="badge bg-danger mt-2">Perlu Perhatian!</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bimbingan Pending - Priority -->
@if($bimbinganPending->count() > 0)
<div class="card mb-4 border-danger border-2 shadow-sm">
    <div class="card-header text-white" style="background: var(--unej-red);">
        <h5 class="mb-0">
            <i class="bi bi-exclamation-circle-fill"></i>
            Bimbingan Menunggu Review ({{ $bimbinganPending->count() }})
        </h5>
    </div>
    <div class="card-body">
        @foreach($bimbinganPending as $bimbingan)
            <div class="card mb-3 border-start border-danger border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-person-circle" style="font-size: 1.5rem; color: var(--unej-red);"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $bimbingan->mahasiswa->name }}</h6>
                                    <p class="mb-2">{{ $bimbingan->judul }}</p>
                                    <div class="d-flex gap-2 align-items-center flex-wrap">
                                        <span class="badge {{ $bimbingan->fase == 'sempro' ? 'bg-info' : 'bg-primary' }}">
                                            {{ strtoupper($bimbingan->fase) }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar"></i>
                                            {{ $bimbingan->tanggal_upload->format('d M Y, H:i') }}
                                        </small>
                                        <small class="text-danger">
                                            <i class="bi bi-clock"></i>
                                            {{ $bimbingan->tanggal_upload->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('dosen.bimbingan.review', $bimbingan->id) }}"
                               class="btn btn-unej-primary">
                                <i class="bi bi-eye-fill"></i> Review Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@else
<div class="alert alert-success border-0 shadow-sm mb-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-check-circle-fill me-3" style="font-size: 2rem;"></i>
        <div>
            <h6 class="mb-0">Semua Bimbingan Sudah Direview!</h6>
            <small>Tidak ada bimbingan yang menunggu review saat ini.</small>
        </div>
    </div>
</div>
@endif

<!-- Mahasiswa Bimbingan Overview -->
@if($mahasiswaBimbingan->count() > 0)
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-people-fill"></i> Mahasiswa Bimbingan Saya
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($mahasiswaBimbingan as $mhs)
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1">{{ $mhs->name }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-card-text"></i> {{ $mhs->nim_nip }}
                                    </small>
                                </div>
                                <div>
                                    @if($mhs->statusMahasiswa?->layak_sidang)
                                        <span class="badge bg-success">
                                            <i class="bi bi-trophy-fill"></i> Layak Sidang
                                        </span>
                                    @elseif($mhs->statusMahasiswa?->layak_sempro)
                                        <span class="badge" style="background: var(--unej-yellow); color: #333;">
                                            <i class="bi bi-check-circle-fill"></i> Layak Sempro
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-hourglass-split"></i> Bimbingan Awal
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="fw-bold">{{ $mhs->statusMahasiswa?->getProgresPercentage() ?? 0 }}%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar"
                                         role="progressbar"
                                         style="width: {{ $mhs->statusMahasiswa?->getProgresPercentage() ?? 0 }}%; background: linear-gradient(135deg, var(--unej-red), var(--unej-green));">
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('dosen.mahasiswa.show', $mhs->id) }}"
                               class="btn btn-sm btn-outline-primary w-100">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('dosen.mahasiswa.index') }}" class="btn btn-unej-primary">
                <i class="bi bi-arrow-right-circle"></i> Lihat Semua Mahasiswa
            </a>
        </div>
    </div>
</div>
@endif

<!-- Riwayat Bimbingan -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-clock-history"></i> Riwayat Bimbingan Terbaru
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Mahasiswa</th>
                        <th>Judul</th>
                        <th>Fase</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBimbingan as $bimbingan)
                        <tr>
                            <td>
                                <small>{{ $bimbingan->tanggal_upload->format('d M Y') }}</small><br>
                                <small class="text-muted">{{ $bimbingan->tanggal_upload->format('H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ $bimbingan->mahasiswa->name }}</strong><br>
                                <small class="text-muted">{{ $bimbingan->mahasiswa->nim_nip }}</small>
                            </td>
                            <td>{{ Str::limit($bimbingan->judul, 40) }}</td>
                            <td>
                                <span class="badge {{ $bimbingan->fase == 'sempro' ? 'bg-info' : 'bg-primary' }}">
                                    {{ strtoupper($bimbingan->fase) }}
                                </span>
                            </td>
                            <td>
                                <span class="{{ $bimbingan->getStatusBadge() }}">
                                    {{ $bimbingan->getStatusText() }}
                                </span>
                            </td>
                            <td>
                                @if($bimbingan->status == 'pending')
                                    <a href="{{ route('dosen.bimbingan.review', $bimbingan->id) }}"
                                       class="btn btn-sm btn-unej-primary">
                                        <i class="bi bi-eye"></i> Review
                                    </a>
                                @else
                                    <a href="{{ route('dosen.mahasiswa.show', $bimbingan->mahasiswa_id) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">Belum ada riwayat bimbingan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
