{{-- File: resources/views/mahasiswa/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-person-circle"></i> Dashboard Mahasiswa</h2>
        <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}</p>
    </div>
    <a href="{{ route('mahasiswa.bimbingan.index') }}" class="btn btn-unej-primary">
        <i class="bi bi-file-earmark-arrow-up"></i> Ajukan Bimbingan
    </a>
</div>

<!-- Status Progres Card -->
<div class="card mb-4 border-0 shadow-lg">
    <div class="card-header text-white" style="background: linear-gradient(135deg, var(--unej-red), var(--unej-green));">
        <h5 class="mb-0">
            <i class="bi bi-graph-up-arrow"></i> Progres Bimbingan Tugas Akhir
        </h5>
    </div>
    <div class="card-body p-4">
        <!-- Status Badge -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Status Saat Ini:</h4>
                <h3 class="mb-0 fw-bold" style="color: var(--unej-red);">
                    {{ $status->getStatusText() }}
                </h3>
            </div>
            <div class="text-end">
                <div class="display-4 fw-bold" style="color: var(--unej-green);">
                    {{ $status->getProgresPercentage() }}%
                </div>
                <small class="text-muted">Progres Keseluruhan</small>
            </div>
        </div>

        <!-- Progress Bar Utama -->
        <div class="mb-4">
            <div class="progress" style="height: 35px; border-radius: 10px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated"
                     role="progressbar"
                     style="width: {{ $status->getProgresPercentage() }}%;
                            background: linear-gradient(135deg, var(--unej-red), var(--unej-yellow), var(--unej-green));"
                     aria-valuenow="{{ $status->getProgresPercentage() }}"
                     aria-valuemin="0"
                     aria-valuemax="100">
                    <strong style="font-size: 1.1rem;">{{ $status->getProgresPercentage() }}%</strong>
                </div>
            </div>
        </div>

        <!-- Milestone Cards -->
        <div class="row g-3">
            <!-- Fase 1: Bimbingan Sempro -->
            <div class="col-md-4">
                <div class="card h-100 {{ $status->getProgresPercentage() >= 0 ? 'border-success' : 'border-secondary' }} border-2">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($status->getProgresPercentage() >= 0)
                                <i class="bi bi-1-circle-fill text-success" style="font-size: 3rem;"></i>
                            @else
                                <i class="bi bi-1-circle text-secondary" style="font-size: 3rem;"></i>
                            @endif
                        </div>
                        <h6 class="fw-bold mb-2">Bimbingan Sempro</h6>
                        <p class="small text-muted mb-0">Upload & revisi dokumen proposal</p>
                        @if($status->getProgresPercentage() >= 0)
                            <span class="badge bg-success mt-2">
                                <i class="bi bi-check-lg"></i> Aktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Fase 2: Layak Sempro -->
            <div class="col-md-4">
                <div class="card h-100 {{ $status->layak_sempro ? 'border-success' : 'border-secondary' }} border-2">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($status->layak_sempro)
                                <i class="bi bi-2-circle-fill text-success" style="font-size: 3rem;"></i>
                            @else
                                <i class="bi bi-2-circle text-secondary" style="font-size: 3rem;"></i>
                            @endif
                        </div>
                        <h6 class="fw-bold mb-2">Layak Sempro</h6>
                        <p class="small text-muted mb-0">Disetujui untuk seminar proposal</p>
                        @if($status->layak_sempro)
                            <span class="badge bg-success mt-2">
                                <i class="bi bi-check-circle-fill"></i> Selesai
                            </span>
                            <p class="small text-muted mt-2 mb-0">
                                <i class="bi bi-calendar"></i>
                                {{ $status->tanggal_layak_sempro?->format('d M Y') }}
                            </p>
                        @else
                            <span class="badge bg-secondary mt-2">Menunggu</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Fase 3: Layak Sidang -->
            <div class="col-md-4">
                <div class="card h-100 {{ $status->layak_sidang ? 'border-success' : 'border-secondary' }} border-2">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($status->layak_sidang)
                                <i class="bi bi-3-circle-fill text-success" style="font-size: 3rem;"></i>
                            @else
                                <i class="bi bi-3-circle text-secondary" style="font-size: 3rem;"></i>
                            @endif
                        </div>
                        <h6 class="fw-bold mb-2">Layak Sidang</h6>
                        <p class="small text-muted mb-0">Siap sidang skripsi</p>
                        @if($status->layak_sidang)
                            <span class="badge bg-success mt-2">
                                <i class="bi bi-trophy-fill"></i> Selesai
                            </span>
                            <p class="small text-muted mt-2 mb-0">
                                <i class="bi bi-calendar"></i>
                                {{ $status->tanggal_layak_sidang?->format('d M Y') }}
                            </p>
                        @else
                            <span class="badge bg-secondary mt-2">Menunggu</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Step Info -->
        @if(!$status->layak_sidang)
        <div class="alert alert-info border-0 mt-4 mb-0">
            <div class="d-flex align-items-center">
                <i class="bi bi-lightbulb-fill me-3" style="font-size: 2rem;"></i>
                <div>
                    <strong>Langkah Selanjutnya:</strong>
                    @if(!$status->layak_sempro)
                        <p class="mb-0">Upload dokumen bimbingan dan tunggu persetujuan dosen untuk kelayakan sempro.</p>
                    @else
                        <p class="mb-0">Upload dokumen skripsi lengkap dan tunggu persetujuan dosen untuk kelayakan sidang.</p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Statistik Bimbingan -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-file-earmark-text-fill" style="font-size: 2.5rem; color: var(--unej-red);"></i>
                <h3 class="mt-3 mb-1 fw-bold">{{ $totalBimbingan }}</h3>
                <p class="text-muted mb-0">Total Upload Bimbingan</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; color: var(--unej-green);"></i>
                <h3 class="mt-3 mb-1 fw-bold">{{ $bimbinganApproved }}</h3>
                <p class="text-muted mb-0">Bimbingan Disetujui</p>
            </div>
        </div>
    </div>
</div>

<!-- Dosen Pembimbing -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-person-badge"></i> Dosen Pembimbing
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($dosenPembimbing as $dosen)
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--unej-red), var(--unej-yellow));">
                                        <i class="bi bi-person-fill text-white" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-secondary mb-2">
                                        {{ $dosen->pivot->jenis_pembimbing == 'pembimbing_1' ? 'Pembimbing 1' : 'Pembimbing 2' }}
                                    </span>
                                    <h6 class="mb-2 fw-bold">{{ $dosen->name }}</h6>
                                    <p class="mb-1 small">
                                        <i class="bi bi-envelope"></i> {{ $dosen->email }}
                                    </p>
                                    <p class="mb-0 small">
                                        <i class="bi bi-telephone"></i> {{ $dosen->phone ?? 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Dosen pembimbing belum ditentukan. Hubungi admin prodi.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Riwayat Bimbingan -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-clock-history"></i> Riwayat Bimbingan
        </h5>
        <a href="{{ route('mahasiswa.riwayat.export') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-printer"></i> Cetak Riwayat
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Judul</th>
                        <th>Dosen</th>
                        <th>Fase</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatBimbingan as $index => $bimbingan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <small>{{ $bimbingan->tanggal_upload->format('d M Y') }}</small><br>
                                <small class="text-muted">{{ $bimbingan->tanggal_upload->format('H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ Str::limit($bimbingan->judul, 40) }}</strong>
                                @if($bimbingan->komentar_dosen)
                                    <br><small class="text-muted">
                                        <i class="bi bi-chat-left-text"></i> Ada komentar dosen
                                    </small>
                                @endif
                            </td>
                            <td>{{ $bimbingan->dosen->name }}</td>
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
                                <a href="{{ route('mahasiswa.bimbingan.show', $bimbingan->id) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3 mb-3">Belum ada riwayat bimbingan</p>
                                <a href="{{ route('mahasiswa.bimbingan.index') }}" class="btn btn-unej-primary">
                                    <i class="bi bi-upload"></i> Ajukan Bimbingan Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
