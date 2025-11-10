@extends('layouts.app')

@section('title', 'Laporan Aktivitas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-file-earmark-bar-graph-fill"></i> Laporan Aktivitas Bimbingan</h2>
    <button onclick="window.print()" class="btn btn-unej-primary">
        <i class="bi bi-printer"></i> Cetak Laporan
    </button>
</div>

<!-- Statistik Keseluruhan -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-people-fill" style="font-size: 2.5rem; color: var(--unej-red);"></i>
                <h3 class="mt-3">{{ $totalMahasiswa }}</h3>
                <p class="text-muted mb-0">Total Mahasiswa</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-person-badge-fill" style="font-size: 2.5rem; color: var(--unej-yellow);"></i>
                <h3 class="mt-3">{{ $totalDosen }}</h3>
                <p class="text-muted mb-0">Total Dosen</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; color: var(--unej-green);"></i>
                <h3 class="mt-3">{{ $layakSempro }}</h3>
                <p class="text-muted mb-0">Layak Sempro</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-trophy-fill" style="font-size: 2.5rem; color: var(--unej-green);"></i>
                <h3 class="mt-3">{{ $layakSidang }}</h3>
                <p class="text-muted mb-0">Layak Sidang</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Per Dosen -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-bar-chart-fill"></i> Statistik Bimbingan Per Dosen
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dosen</th>
                        <th>NIP</th>
                        <th>Jumlah Mahasiswa</th>
                        <th>Total Bimbingan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dosenStats as $index => $dosen)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $dosen->name }}</td>
                            <td>{{ $dosen->nim_nip }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $dosen->mahasiswa_bimbingan_count }} mahasiswa
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    {{ $dosen->bimbingan_as_dosen_count }} bimbingan
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .navbar, .sidebar, .btn, .card-header .btn {
            display: none !important;
        }
        .col-md-9, .col-lg-10 {
            max-width: 100% !important;
        }
    }
</style>
@endsection
