{{-- File: resources/views/admin/mahasiswa/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Kelola Mahasiswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people-fill"></i> Kelola Data Mahasiswa</h2>
    <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-unej-primary">
        <i class="bi bi-plus-circle"></i> Tambah Mahasiswa Baru
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-table"></i> Daftar Mahasiswa
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Dosen Pembimbing</th>
                        <th>Status</th>
                        <th>Progres</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $index => $mhs)
                        <tr>
                            <td>{{ $mahasiswa->firstItem() + $index }}</td>
                            <td><strong>{{ $mhs->nim_nip }}</strong></td>
                            <td>{{ $mhs->name }}</td>
                            <td>{{ $mhs->email }}</td>
                            <td>
                                @if($mhs->dosenPembimbing->count() > 0)
                                    <small>
                                        @foreach($mhs->dosenPembimbing as $dosen)
                                            <span class="badge bg-secondary">{{ $dosen->name }}</span>
                                        @endforeach
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($mhs->statusMahasiswa?->layak_sidang)
                                    <span class="badge bg-success">Layak Sidang</span>
                                @elseif($mhs->statusMahasiswa?->layak_sempro)
                                    <span class="badge bg-warning">Layak Sempro</span>
                                @else
                                    <span class="badge bg-secondary">Bimbingan Awal</span>
                                @endif
                            </td>
                            <td>
                                <div class="progress" style="height: 20px; width: 100px;">
                                    <div class="progress-bar bg-success"
                                         role="progressbar"
                                         style="width: {{ $mhs->statusMahasiswa?->getProgresPercentage() ?? 0 }}%">
                                        {{ $mhs->statusMahasiswa?->getProgresPercentage() ?? 0 }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada data mahasiswa
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $mahasiswa->links() }}
        </div>
    </div>
</div>
@endsection
