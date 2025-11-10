@extends('layouts.app')

@section('title', 'Riwayat Bimbingan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-file-text"></i> Riwayat Bimbingan Lengkap
        </span>
        <button onclick="window.print()" class="btn btn-unej-primary btn-sm">
            <i class="bi bi-printer"></i> Cetak / PDF
        </button>
    </div>
    <div class="card-body">
        <!-- Header -->
        <div class="text-center mb-4">
            <h3>RIWAYAT BIMBINGAN TUGAS AKHIR</h3>
            <h4>UNIVERSITAS JEMBER</h4>
            <hr>
        </div>

        <!-- Data Mahasiswa -->
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>Nama</strong></td>
                        <td>: {{ $mahasiswa->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIM</strong></td>
                        <td>: {{ $mahasiswa->nim_nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>: {{ $mahasiswa->email }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>Status</strong></td>
                        <td>:
                            @if($status?->layak_sidang)
                                Layak Sidang
                            @elseif($status?->layak_sempro)
                                Layak Sempro
                            @else
                                Bimbingan Awal
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Progres</strong></td>
                        <td>: {{ $status?->getProgresPercentage() ?? 0 }}%</td>
                    </tr>
                    <tr>
                        <td><strong>Total Bimbingan</strong></td>
                        <td>: {{ $bimbingan->count() }} kali</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <h5 class="mb-3">Detail Riwayat Bimbingan</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Fase</th>
                        <th>Judul</th>
                        <th>Dosen</th>
                        <th>Status</th>
                        <th>Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bimbingan as $index => $b)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $b->tanggal_upload->format('d/m/Y') }}</td>
                            <td>{{ strtoupper($b->fase) }}</td>
                            <td>{{ $b->judul }}</td>
                            <td>{{ $b->dosen->name }}</td>
                            <td>{{ $b->getStatusText() }}</td>
                            <td>{{ $b->komentar_dosen ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada riwayat bimbingan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="mt-5">
            <p class="text-muted">
                <small>
                    Dokumen ini dicetak pada: {{ now()->format('d F Y, H:i') }} WIB
                </small>
            </p>
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
