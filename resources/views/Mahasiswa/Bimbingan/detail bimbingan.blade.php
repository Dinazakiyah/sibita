@extends('layouts.app')

@section('title', 'Detail Bimbingan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-file-text-fill"></i> Detail Bimbingan
            </div>
            <div class="card-body">
                <!-- Info Bimbingan -->
                <div class="mb-3">
                    <label class="fw-bold">Judul:</label>
                    <p>{{ $bimbingan->judul }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Fase:</label>
                    <span class="badge {{ $bimbingan->fase == 'sempro' ? 'bg-info' : 'bg-primary' }}">
                        {{ strtoupper($bimbingan->fase) }}
                    </span>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Dosen Pembimbing:</label>
                    <p>{{ $bimbingan->dosen->name }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Deskripsi:</label>
                    <p>{{ $bimbingan->deskripsi ?? '-' }}</p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Status:</label>
                    <span class="{{ $bimbingan->getStatusBadge() }}">
                        {{ $bimbingan->getStatusText() }}
                    </span>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Tanggal Upload:</label>
                    <p>{{ $bimbingan->tanggal_upload->format('d F Y, H:i') }}</p>
                </div>

                @if($bimbingan->komentar_dosen)
                    <div class="mb-3">
                        <label class="fw-bold">Komentar Dosen:</label>
                        <div class="alert alert-info">
                            {{ $bimbingan->komentar_dosen }}
                        </div>
                        @if($bimbingan->tanggal_revisi)
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i>
                                {{ $bimbingan->tanggal_revisi->format('d F Y, H:i') }}
                            </small>
                        @endif
                    </div>
                @endif

                <div class="mb-3">
                    <label class="fw-bold">File:</label>
                    <div>
                        <a href="{{ route('mahasiswa.bimbingan.download', $bimbingan->id) }}"
                           class="btn btn-info">
                            <i class="bi bi-download"></i> Download File
                        </a>
                    </div>
                </div>

                <hr>

                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
