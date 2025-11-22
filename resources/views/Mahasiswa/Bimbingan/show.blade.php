@extends('layouts.app')

@section('title','Detail Bimbingan (Mahasiswa)')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-file-earmark-text"></i> Detail Bimbingan
        </h5>
        <a href="{{ route('mahasiswa.bimbingan.index') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-8">
                <h4 class="fw-bold mb-3">{{ $bimbingan->judul ?? 'Judul tidak tersedia' }}</h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Dosen Pembimbing</p>
                        <h6 class="fw-bold">{{ $bimbingan->dosen->name ?? '-' }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Tanggal Dimulai</p>
                        <h6 class="fw-bold">{{ $bimbingan->created_at->format('d M Y') }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <p class="text-muted mb-2">Status Saat Ini</p>
                        <h5 class="fw-bold" style="color: var(--unej-red);">
                            {{ ucfirst($bimbingan->status ?? 'pending') }}
                        </h5>
                        @if($bimbingan->percentage)
                            <p class="mb-1">
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-star-fill"></i> {{ number_format($bimbingan->percentage, 1) }}%
                                </span>
                            </p>
                            <small class="text-muted">Penilaian Dosen</small>
                        @endif

                        <!-- Status Badge -->
                        @if($bimbingan->status === 'approved')
                            <div class="mt-2">
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle-fill"></i> Bimbingan Disetujui
                                </span>
                            </div>
                        @elseif($bimbingan->status === 'revisi')
                            <div class="mt-2">
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Perlu Revisi
                                </span>
                            </div>
                        @else
                            <div class="mt-2">
                                <span class="badge bg-secondary fs-6">
                                    <i class="bi bi-clock"></i> Dalam Proses
                                </span>
                            </div>
                        @endif
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-calendar"></i> Updated: {{ $bimbingan->updated_at->format('d M Y') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submissions Section -->
        <hr class="my-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-cloud-upload"></i> Upload Submissions
            </h6>
            <a href="{{ route('mahasiswa.uploads.create', $bimbingan->id) }}" class="btn btn-sm btn-unej-primary">
                <i class="bi bi-plus-circle"></i> Upload Baru
            </a>
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $index => $s)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <small>{{ $s->submitted_at ? $s->submitted_at->format('d M Y') : $s->created_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $s->file_name }}</strong>
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
                                        <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Menunggu Review</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('mahasiswa.submissions.show', $s->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Belum ada submission.
                <a href="{{ route('mahasiswa.uploads.create', $bimbingan->id) }}" class="alert-link">Upload sekarang</a>
            </div>
        @endif
    </div>
</div>
@endsection
