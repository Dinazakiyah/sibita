@extends('layouts.app')

@section('title', 'Riwayat Booking Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Riwayat Booking Jadwal Bimbingan</h4>
                    <a href="{{ route('mahasiswa.appointments.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Booking Baru
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
                                        <th>Dosen</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $index => $appointment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $appointment->dosen->name }}</td>
                                            <td>{{ $appointment->scheduled_date->format('d M Y') }}</td>
                                            <td>{{ $appointment->scheduled_time }}</td>
                                            <td>
                                                <span class="badge {{ $appointment->getStatusBadge() }}">
                                                    {{ $appointment->getStatusText() }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($appointment->notes)
                                                    <small>{{ Str::limit($appointment->notes, 50) }}</small>
                                                @else
                                                    <em class="text-muted">Tidak ada</em>
                                                @endif
                                            </td>
                                            <td>
                                                @if($appointment->status === 'pending')
                                                    <form action="{{ route('mahasiswa.appointments.cancel', $appointment->id) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                                                        @csrf
                                                        @method('POST')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-x-circle"></i> Batal
                                                        </button>
                                                    </form>
                                                @elseif($appointment->status === 'rejected' && $appointment->reason_for_rejection)
                                                    <button type="button" class="btn btn-sm btn-info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#reasonModal{{ $appointment->id }}">
                                                        <i class="bi bi-info-circle"></i> Alasan
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                        @if($appointment->status === 'rejected' && $appointment->reason_for_rejection)
                                            <!-- Modal for rejection reason -->
                                            <div class="modal fade" id="reasonModal{{ $appointment->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Alasan Penolakan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ $appointment->reason_for_rejection }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">Belum ada booking jadwal</h5>
                            <p class="text-muted">Anda belum melakukan booking jadwal bimbingan dengan dosen.</p>
                            <a href="{{ route('mahasiswa.appointments.index') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Booking Jadwal Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
