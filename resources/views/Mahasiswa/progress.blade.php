@extends('layouts.app')

@section('title', 'Progress Bimbingan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-graph-up"></i> Progress Keseluruhan Bimbingan</h2>
    <a href="{{ route('mahasiswa.bimbingan.index') }}" class="btn btn-primary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if(isset($progressData) && $progressData->count())
    <div class="row g-3">
        @foreach($progressData as $progress)
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text"></i> 
                            {{ $progress['bimbingan']->judul ?? 'Bimbingan' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted mb-1">Dosen Pembimbing</p>
                            <h6 class="fw-bold">{{ $progress['bimbingan']->dosen->name ?? '-' }}</h6>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Progress Upload</span>
                                <span class="badge bg-primary">{{ $progress['percentage'] }}%</span>
                            </div>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                     role="progressbar"
                                     style="width: {{ $progress['percentage'] }}%; 
                                            background: linear-gradient(135deg, var(--unej-red), var(--unej-green));"
                                     aria-valuenow="{{ $progress['percentage'] }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="fw-bold mb-1" style="color: var(--unej-red);">
                                        {{ $progress['total_submissions'] }}
                                    </h4>
                                    <small class="text-muted">Total Upload</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="fw-bold mb-1" style="color: var(--unej-green);">
                                        {{ $progress['approved_submissions'] }}
                                    </h4>
                                    <small class="text-muted">Disetujui</small>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('mahasiswa.bimbingan.show', $progress['bimbingan']->id) }}" 
                           class="btn btn-outline-primary w-100">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Summary Card -->
    <div class="card mt-4 border-0 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Ringkasan Keseluruhan</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="p-3">
                        <h3 class="fw-bold" style="color: var(--unej-red);">
                            {{ $progressData->sum('total_submissions') }}
                        </h3>
                        <p class="text-muted mb-0">Total Bimbingan</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <h3 class="fw-bold" style="color: var(--unej-green);">
                            {{ $progressData->sum('approved_submissions') }}
                        </h3>
                        <p class="text-muted mb-0">Disetujui</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <h3 class="fw-bold" style="color: var(--unej-blue);">
                            {{ $progressData->sum('total_submissions') - $progressData->sum('approved_submissions') }}
                        </h3>
                        <p class="text-muted mb-0">Menunggu</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <h3 class="fw-bold">
                            {{ $progressData->count() > 0 ? round($progressData->avg('percentage')) : 0 }}%
                        </h3>
                        <p class="text-muted mb-0">Rata-rata Progress</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        Belum ada data bimbingan. 
        <a href="{{ route('mahasiswa.bimbingan.index') }}" class="alert-link">Ajukan bimbingan sekarang</a>
    </div>
@endif

@endsection
