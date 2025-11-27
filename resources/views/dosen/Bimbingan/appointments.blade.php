@extends('layouts.app')

@section('title', 'Permintaan Jadwal Bimbingan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Permintaan Jadwal Bimbingan</h4>
                    <a href="{{ route('dosen.schedule.my') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-calendar-check"></i> Jadwal Saya
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
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
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
                                            <td>{{ $appointment->created_at->diffForHumans() }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <form action="{{ route('dosen.appointments.approve', $appointment->id) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('POST')
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                                onclick="return confirm('Apakah Anda yakin ingin menyetujui jadwal ini?')">
                                                            <i class="bi bi-check-circle"></i> Setujui
                                                        </button>
                                                    </form>

                                                    <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#rejectModal{{ $appointment->id }}">
                                                        <i class="bi bi-x-circle"></i> Tolak
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $appointment->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Permintaan Jadwal</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('dosen.appointments.reject', $appointment->id) }}" method="POST">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="reason{{ $appointment->id }}" class="form-label">
                                                                    Alasan Penolakan <span class="text-danger">*</span>
                                                                </label>
                                                                <textarea class="form-control" id="reason{{ $appointment->id }}"
                                                                          name="reason" rows="3" required
                                                                          placeholder="Berikan alasan penolakan..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Tolak Jadwal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">Tidak ada permintaan jadwal</h5>
                            <p class="text-muted">Belum ada mahasiswa yang mengajukan jadwal bimbingan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
