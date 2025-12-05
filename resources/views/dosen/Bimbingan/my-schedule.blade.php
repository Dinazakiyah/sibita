@extends('layouts.app')

@section('title', 'Jadwal Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Jadwal Bimbingan Saya</h4>
                    <a href="{{ route('dosen.appointments.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-calendar-plus"></i> Lihat Permintaan Jadwal
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Catatan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $index => $appointment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $appointment->mahasiswa->name }}</strong><br>
                                                <small class="text-muted">{{ $appointment->mahasiswa->email }}</small>
                                            </td>
                                            <td>{{ $appointment->scheduled_date->format('d M Y') }}</td>
                                            <td>{{ $appointment->scheduled_time }}</td>
                                            <td>
                                                @if($appointment->notes)
                                                    <small>{{ Str::limit($appointment->notes, 50) }}</small>
                                                @else
                                                    <em class="text-muted">Tidak ada</em>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $appointment->getStatusBadge() }}">
                                                    {{ $appointment->getStatusText() }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-check text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">Belum ada jadwal disetujui</h5>
                            <p class="text-muted">Jadwal bimbingan yang disetujui akan muncul di sini.</p>
                            <a href="{{ route('dosen.appointments.index') }}" class="btn btn-primary">
                                <i class="bi bi-calendar-plus"></i> Lihat Permintaan Jadwal
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
