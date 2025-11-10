{{-- File: resources/views/dosen/mahasiswa/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Mahasiswa')

@section('content')
<div class="row">
    <!-- Info Mahasiswa -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person-circle"></i> Profil Mahasiswa
            </div>
            <div class="card-body text-center">
                <i class="bi bi-person-circle" style="font-size: 5rem; color: var(--unej-red);"></i>
                <h4 class="mt-3">{{ $mahasiswa->name }}</h4>
                <p class="text-muted mb-1">NIM: {{ $mahasiswa->nim_nip }}</p>
                <p class="text-muted mb-3">
                    <i class="bi bi-envelope"></i> {{ $mahasiswa->email }}
                </p>

                <!-- Status Progres -->
                <div class="alert {{ $mahasiswa->statusMahasiswa?->layak_sidang ? 'alert-success' : 'alert-warning' }}">
                    <strong>Status:</strong>
                    <p class="mb-0">{{ $mahasiswa->statusMahasiswa?->getStatusText() ?? 'Belum Ada Status' }}</p>
                </div>

                <!-- Progress Bar -->
                @if($mahasiswa->statusMahasiswa)
                    <div class="mb-3">
                        <label class="small fw-bold">Progres Keseluruhan</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar"
                                 style="width: {{ $mahasiswa->statusMahasiswa->getProgresPercentage() }}%; background: linear-gradient(135deg, var(--unej-red), var(--unej-green));"
                                 role="progressbar">
                                {{ $mahasiswa->statusMahasiswa->getProgresPercentage() }}%
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    @if($mahasiswa->statusMahasiswa && !$mahasiswa->statusMahasiswa->layak_sempro)
                        <form action="{{ route('dosen.approve.sempro', $mahasiswa->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="btn btn-unej-warning w-100"
                                    onclick="return confirm('Yakin menyetujui mahasiswa ini layak sempro?')">
                                <i class="bi bi-check-circle"></i> Setujui Layak Sempro
                            </button>
                        </form>
                    @endif

                    @if($mahasiswa->statusMahasiswa && $mahasiswa->statusMahasiswa->layak_sempro && !$mahasiswa->statusMahasiswa->layak_sidang)
                        <form action="{{ route('dosen.approve.sidang', $mahasiswa->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="btn btn-unej-success w-100"
                                    onclick="return confirm('Yakin menyetujui mahasiswa ini layak sidang?')">
                                <i class="bi bi-check-circle-fill"></i> Setujui Layak Sidang
                            </button>
                        </form>
                    @endif

                    @if($mahasiswa->statusMahasiswa?->layak_sidang)
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-trophy-fill"></i>
                            <strong>Mahasiswa Sudah Layak Sidang!</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Bimbingan -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-clock-history"></i> Riwayat Bimbingan
                </span>
                <span class="badge badge-unej">Total: {{ $bimbingan->count() }}</span>
            </div>
            <div class="card-body">
                @forelse($bimbingan as $b)
                    <div class="card mb-3 border-start border-4 {{ $b->status == 'approved' ? 'border-success' : ($b->status == 'revisi' ? 'border-danger' : 'border-warning') }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $b->judul }}</h6>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-calendar"></i> {{ $b->tanggal_upload->format('d F Y, H:i') }}
                                    </p>

                                    @if($b->deskripsi)
                                        <p class="mb-2"><strong>Deskripsi:</strong> {{ $b->deskripsi }}</p>
                                    @endif

                                    @if($b->komentar_dosen)
                                        <div class="alert alert-light mb-2">
                                            <strong><i class="bi bi-chat-left-text"></i> Komentar Dosen:</strong>
                                            <p class="mb-0">{{ $b->komentar_dosen }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="ms-3 text-end">
                                    <span class="{{ $b->getStatusBadge() }} mb-2 d-inline-block">
                                        {{ $b->getStatusText() }}
                                    </span>
                                    <br>
                                    <span class="badge {{ $b->fase == 'sempro' ? 'bg-info' : 'bg-primary' }}">
                                        {{ strtoupper($b->fase) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-3 d-flex gap-2">
                                <a href="{{ asset('storage/' . $b->file_path) }}"
                                   class="btn btn-sm btn-info"
                                   target="_blank">
                                    <i class="bi bi-download"></i> Unduh
                                </a>

                                @if($b->status == 'pending')
                                    <a href="{{ route('dosen.bimbingan.review', $b->id) }}"
                                       class="btn btn-sm btn-unej-primary">
                                        <i class="bi bi-eye"></i> Review
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-3">Belum ada riwayat bimbingan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
