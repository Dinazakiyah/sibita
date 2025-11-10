@extends('layouts.app')

@section('title', 'Kelola Dosen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-badge-fill"></i> Kelola Data Dosen</h2>
    <a href="{{ route('admin.dosen.create') }}" class="btn btn-unej-primary">
        <i class="bi bi-plus-circle"></i> Tambah Dosen Baru
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-table"></i> Daftar Dosen Pembimbing
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Jumlah Mahasiswa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dosen as $index => $d)
                        <tr>
                            <td>{{ $dosen->firstItem() + $index }}</td>
                            <td><strong>{{ $d->nim_nip }}</strong></td>
                            <td>{{ $d->name }}</td>
                            <td>{{ $d->email }}</td>
                            <td>{{ $d->phone ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $d->mahasiswa_bimbingan_count }} mahasiswa
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada data dosen
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $dosen->links() }}
        </div>
    </div>
</div>
@endsection
