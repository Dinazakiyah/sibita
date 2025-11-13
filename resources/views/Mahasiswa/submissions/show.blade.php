@extends('layouts.app')

@section('title','Detail Submission')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-file-earmark"></i> Detail Submission
        </h5>
        <a href="javascript:history.back()" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <!-- File Info -->
        <div class="row mb-4">
            <div class="col-md-6">
                <p class="text-muted mb-1">Nama File</p>
                <h6 class="fw-bold mb-3">{{ $submission->file_name ?? '-' }}</h6>

                <p class="text-muted mb-1">Tipe Upload</p>
                <h6 class="fw-bold mb-3">
                    <span class="badge bg-info">{{ ucfirst($submission->file_type) }}</span>
                </h6>

                <p class="text-muted mb-1">Ukuran File</p>
                <h6 class="fw-bold mb-3">{{ formatBytes($submission->file_size ?? 0) }}</h6>
            </div>
            <div class="col-md-6">
                <p class="text-muted mb-1">Status</p>
                <h6 class="fw-bold mb-3">
                    @if($submission->status === 'approved')
                        <span class="badge bg-success"><i class="bi bi-check-lg"></i> Disetujui</span>
                    @elseif($submission->status === 'rejected')
                        <span class="badge bg-danger"><i class="bi bi-x-lg"></i> Ditolak</span>
                    @else
                        <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Menunggu Review</span>
                    @endif
                </h6>

                <p class="text-muted mb-1">Tanggal Upload</p>
                <h6 class="fw-bold">{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : $submission->created_at->format('d M Y, H:i') }}</h6>
            </div>
        </div>

        @if($submission->description)
            <div class="alert alert-light border">
                <p class="mb-0">{{ $submission->description }}</p>
            </div>
        @endif

        <!-- Download Link -->
        <div class="d-flex gap-2 mb-4">
            <a href="{{ asset('storage/' . $submission->file_path) }}" class="btn btn-outline-primary" download>
                <i class="bi bi-download"></i> Download File
            </a>
            <a href="{{ asset('storage/' . $submission->file_path) }}" class="btn btn-outline-secondary" target="_blank">
                <i class="bi bi-eye"></i> Lihat Preview
            </a>
        </div>

        <hr class="my-4">

        <!-- Comments Section -->
        <h6 class="fw-bold mb-3">
            <i class="bi bi-chat-left-text"></i> Komentar dari Dosen
            @if($comments && $comments->count())
                <span class="badge bg-secondary">{{ $comments->count() }}</span>
            @endif
        </h6>

        @forelse($comments as $c)
            <div class="card mb-3 border">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <strong>{{ $c->dosen->name ?? 'Dosen' }}</strong>
                            <p class="text-muted small mb-0">{{ $c->dosen->nim_nip ?? '' }}</p>
                        </div>
                        <small class="text-muted">{{ $c->created_at->format('d M Y, H:i') }}</small>
                    </div>
                    <p class="mb-0">{{ $c->comment }}</p>
                    @if($c->is_pinned)
                        <div class="mt-2">
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-pin-fill"></i> Penting
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Belum ada komentar dari dosen. Silakan tunggu atau hubungi dosen Anda.
            </div>
        @endforelse
    </div>
</div>

{{-- Helper function for formatting bytes --}}
@php
if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
@endphp

@endsection
