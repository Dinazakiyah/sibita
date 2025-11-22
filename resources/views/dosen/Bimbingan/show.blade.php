@extends('layouts.app')

@section('title', 'Detail Bimbingan - Dosen')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text"></i> Detail Bimbingan
                    </h5>
                    <a href="{{ route('dosen.dashboard') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <!-- Bimbingan Info -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h4 class="fw-bold mb-3">{{ $bimbingan->judul ?? 'Judul tidak tersedia' }}</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Mahasiswa</p>
                                    <h6 class="fw-bold">{{ $bimbingan->mahasiswa->name ?? '-' }}</h6>
                                    <small class="text-muted">{{ $bimbingan->mahasiswa->nim_nip ?? '' }}</small>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Fase Bimbingan</p>
                                    <span class="badge bg-primary fs-6">{{ strtoupper($bimbingan->fase ?? 'unknown') }}</span>
                                </div>
                            </div>
                            @if($bimbingan->deskripsi)
                                <div class="mb-3">
                                    <p class="text-muted mb-1">Deskripsi</p>
                                    <div class="bg-light p-3 rounded">{{ $bimbingan->deskripsi }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <p class="text-muted mb-2">Status Bimbingan</p>
                                    <h5 class="fw-bold" style="color: var(--unej-red);">
                                        {{ ucfirst($bimbingan->status ?? 'pending') }}
                                    </h5>
                                    @if($bimbingan->percentage)
                                        <p class="mb-1">
                                            <span class="badge bg-success fs-6">
                                                <i class="bi bi-star-fill"></i> {{ number_format($bimbingan->percentage, 1) }}%
                                            </span>
                                        </p>
                                        <small class="text-muted">Penilaian</small>
                                    @endif
                                    <small class="text-muted d-block mt-2">
                                        <i class="bi bi-calendar"></i> Updated: {{ $bimbingan->updated_at->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Submissions Section -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-cloud-upload"></i> Submissions Mahasiswa
                        </h6>
                        <span class="badge bg-info">{{ $submissions->count() }} submission(s)</span>
                    </div>

                    @if(isset($submissions) && $submissions->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>File</th>
                                        <th>Tipe</th>
                                        <th>Status</th>
                                        <th>Komentar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $index => $s)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <small>{{ $s->submitted_at ? $s->submitted_at->format('d M Y') : $s->created_at->format('d M Y') }}</small>
                                                <br>
                                                <small class="text-muted">{{ $s->submitted_at ? $s->submitted_at->format('H:i') : $s->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $s->file_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ formatBytes($s->file_size) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($s->file_type) }}</span>
                                            </td>
                                            <td>
                                                @if($s->status === 'approved')
                                                    <span class="badge bg-success"><i class="bi bi-check-lg"></i> Disetujui</span>
                                                @elseif($s->status === 'rejected')
                                                    <span class="badge bg-danger"><i class="bi bi-x-lg"></i> Ditolak</span>
                                                @else
                                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Review</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $s->comments->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('dosen.submissions.review', $s->id) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Review & Beri Komentar">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ asset('storage/' . $s->file_path) }}"
                                                       class="btn btn-sm btn-outline-secondary"
                                                       target="_blank"
                                                       title="Lihat Dokumen">
                                                        <i class="bi bi-file-earmark"></i>
                                                    </a>
                                                    <a href="{{ asset('storage/' . $s->file_path) }}"
                                                       class="btn btn-sm btn-outline-info"
                                                       download
                                                       title="Download Dokumen">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Stats -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-light text-center">
                                    <div class="card-body">
                                        <h4 class="text-success">{{ $submissions->where('status', 'approved')->count() }}</h4>
                                        <small class="text-muted">Disetujui</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light text-center">
                                    <div class="card-body">
                                        <h4 class="text-warning">{{ $submissions->where('status', 'submitted')->count() }}</h4>
                                        <small class="text-muted">Menunggu Review</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light text-center">
                                    <div class="card-body">
                                        <h4 class="text-danger">{{ $submissions->where('status', 'rejected')->count() }}</h4>
                                        <small class="text-muted">Ditolak</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light text-center">
                                    <div class="card-body">
                                        <h4 class="text-info">{{ $submissions->sum(function($s) { return $s->comments->count(); }) }}</h4>
                                        <small class="text-muted">Total Komentar</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Belum ada submission dari mahasiswa ini.
                            <br>
                            <small class="text-muted">Mahasiswa akan mengupload dokumen bimbingan melalui dashboard mereka.</small>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    @if($submissions->count() > 0)
                        <div class="mt-4">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-lightning"></i> Aksi Cepat
                            </h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('dosen.submissions.review', $submissions->where('status', 'submitted')->first()?->id ?? $submissions->first()->id) }}"
                                   class="btn btn-unej-primary">
                                    <i class="bi bi-chat-left-text"></i> Review Submission Terbaru
                                </a>
                                <a href="{{ route('dosen.mahasiswa.show', $bimbingan->mahasiswa->id) }}"
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-person"></i> Lihat Detail Mahasiswa
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
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
