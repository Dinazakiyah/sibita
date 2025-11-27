@extends('layouts.app')

@section('title', 'Booking Jadwal Bimbingan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Pilih Jadwal Bimbingan</h4>
                    <a href="{{ route('mahasiswa.appointments.my') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-list"></i> Riwayat Booking
                    </a>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        @foreach($dosens as $dosen)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="mb-0">{{ $dosen->name }}</h5>
                                        <small class="text-muted">{{ $dosen->email }}</small>
                                    </div>
                                    <div class="card-body">
                                        <button
                                            type="button"
                                            class="btn btn-primary w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#bookingModal{{ $dosen->id }}">
                                            <i class="bi bi-calendar-plus"></i> Pilih Jadwal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($dosens->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-person-x text-muted" style="font-size:3rem"></i>
                            <h5 class="mt-3 text-muted">Tidak ada dosen tersedia</h5>
                            <p class="text-muted">Belum ada dosen yang terdaftar di sistem.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@foreach($dosens as $dosen)
<div class="modal fade" id="bookingModal{{ $dosen->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Booking dengan {{ $dosen->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('mahasiswa.appointments.book') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="dosen_id" value="{{ $dosen->id }}">

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date"
                                   class="form-control"
                                   name="scheduled_date"
                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                   required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Waktu</label>
                            <select class="form-control" name="scheduled_time" required>
                                <option value="">Pilih waktu</option>
                                <option>09:00</option>
                                <option>09:30</option>
                                <option>10:00</option>
                                <option>10:30</option>
                                <option>11:00</option>
                                <option>11:30</option>
                                <option>13:00</option>
                                <option>13:30</option>
                                <option>14:00</option>
                                <option>14:30</option>
                                <option>15:00</option>
                                <option>15:30</option>
                                <option>16:00</option>
                                <option>16:30</option>
                            </select>
                        </div>
                    </div>

                    <label class="form-label mt-3">Catatan (Opsional)</label>
                    <textarea class="form-control" name="notes" rows="3"></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Booking Jadwal</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach
