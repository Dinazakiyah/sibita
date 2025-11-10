@extends('layouts.app')

@section('title', 'Mahasiswa Bimbingan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Mahasiswa Bimbingan Saya</h2>
</div>

<div class="row">
    @forelse($mahasiswa as $mhs)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title">{{ $mhs->name }}</h5>
                            <p class="text-muted mb-2">NIM: {{ $mhs->nim_nip }}</p>
                            <p class="mb-2">
                                <i class="bi bi-envelope"></i> {{ $mhs->email }}
                            </p>
                        </div>
                        <div>
                            @if($mhs->statusMahasiswa?->layak_sidang)
                                <span class="badge bg-success">Layak Sidang</span>
                            @elseif($mhs->statusMahasiswa?->layak_sempro)
                                <span class="badge bg-warning">Layak Sempro</span>
                            @else
                                <span class="badge bg-secondary">Bimbingan Awal</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="small fw-bold">Progres:</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success"
                                 role="progressbar"
                                 style="width: {{ $mhs->statusMahasiswa?->getProgresPercentage() ?? 0 }}%">
                                {{ $mhs->statusMahasiswa?->getProgresPercentage() ?? 0 }}%
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('dosen.mahasiswa.show', $mhs->id) }}" class="btn btn-unej-primary btn-sm">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Anda belum memiliki mahasiswa bimbingan.
            </div>
        </div>
    @endforelse
</div>
@endsection
