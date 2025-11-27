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
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        @foreach($dosens as $dosen)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">{{ $dosen->name }}</h5>
                                        <small class="text-muted">{{ $dosen->email }}</small>
                                    </div>
                                    <div class="card-body">
                                        <button type="button" class="btn btn-primary w-100"
                                                data-bs-toggle="modal"
                                                data-bs-target="#bookingModal{{ $dosen->id }}">
                                            <i class="bi bi-calendar-plus"></i> Pilih Jadwal
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Modal -->
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
                                                        <div class="mb-3">
                                                            <label for="scheduled_date{{ $dosen->id }}" class="form-label">Tanggal</label>
                                                            <input type="date" class="form-control"
                                                                   id="scheduled_date{{ $dosen->id }}"
                                                                   name="scheduled_date"
                                                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="scheduled_time{{ $dosen->id }}" class="form-label">Waktu</label>
                                                            <select class="form-control" id="scheduled_time{{ $dosen->id }}"
                                                                    name="scheduled_time" required>
                                                                <option value="">Pilih waktu</option>
                                                                <option value="09:00">09:00</option>
                                                                <option value="09:30">09:30</option>
                                                                <option value="10:00">10:00</option>
                                                                <option value="10:30">10:30</option>
                                                                <option value="11:00">11:00</option>
                                                                <option value="11:30">11:30</option>
                                                                <option value="13:00">13:00</option>
                                                                <option value="13:30">13:30</option>
                                                                <option value="14:00">14:00</option>
                                                                <option value="14:30">14:30</option>
                                                                <option value="15:00">15:00</option>
                                                                <option value="15:30">15:30</option>
                                                                <option value="16:00">16:00</option>
                                                                <option value="16:30">16:30</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="notes{{ $dosen->id }}" class="form-label">Catatan (Opsional)</label>
                                                    <textarea class="form-control" id="notes{{ $dosen->id }}"
                                                              name="notes" rows="3"
                                                              placeholder="Tambahkan catatan untuk dosen..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Booking Jadwal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($dosens->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">Tidak ada dosen tersedia</h5>
                            <p class="text-muted">Belum ada dosen yang terdaftar di sistem.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-update available time slots based on selected date
document.addEventListener('DOMContentLoaded', function() {
    @foreach($dosens as $dosen)
        const dateInput{{ $dosen->id }} = document.getElementById('scheduled_date{{ $dosen->id }}');
        const timeSelect{{ $dosen->id }} = document.getElementById('scheduled_time{{ $dosen->id }}');

        dateInput{{ $dosen->id }}.addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                // Here you could make an AJAX call to get available slots for the selected date
                // For now, we'll just enable all time slots
                timeSelect{{ $dosen->id }}.disabled = false;
            }
        });
    @endforeach
});
</script>
@endsection
