{{-- File: resources/views/mahasiswa/bimbingan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Upload Bimbingan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-upload"></i> Upload Dokumen Bimbingan
            </div>
            <div class="card-body">
                <!-- Info Fase Bimbingan -->
                <div class="alert {{ $faseAktif == 'sempro' ? 'alert-info' : 'alert-success' }}" role="alert">
                    <i class="bi bi-info-circle-fill"></i>
                    <strong>Fase Bimbingan Saat Ini: {{ strtoupper($faseAktif) }}</strong>
                    <p class="mb-0 mt-2">
                        @if($faseAktif == 'sempro')
                            Anda sedang dalam fase bimbingan untuk Seminar Proposal.
                        @else
                            Anda sudah layak sempro dan sekarang dalam fase bimbingan Sidang Skripsi.
                        @endif
                    </p>
                </div>

                <!-- Form Upload -->
                <form action="{{ route('mahasiswa.bimbingan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Pilih Dosen Pembimbing -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-person-badge"></i> Pilih Dosen Pembimbing <span class="text-danger">*</span>
                        </label>
                        <select name="dosen_id" class="form-select" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosenPembimbing as $dosen)
                                <option value="{{ $dosen->id }}">
                                    {{ $dosen->name }}
                                    ({{ $dosen->pivot->jenis_pembimbing == 'pembimbing_1' ? 'Pembimbing 1' : 'Pembimbing 2' }})
                                </option>
                            @endforeach
                        </select>
                        @error('dosen_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Judul Dokumen -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-file-text"></i> Judul Dokumen <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="judul"
                               class="form-control"
                               placeholder="Contoh: Revisi BAB 1 - Pendahuluan"
                               value="{{ old('judul') }}"
                               required>
                        @error('judul')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-card-text"></i> Deskripsi / Catatan
                        </label>
                        <textarea name="deskripsi"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Tulis catatan atau keterangan tambahan...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Upload File -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-paperclip"></i> Upload File (PDF/DOC/DOCX) <span class="text-danger">*</span>
                        </label>
                        <input type="file"
                               name="file"
                               class="form-control"
                               accept=".pdf,.doc,.docx"
                               required>
                        <small class="text-muted">
                            <i class="bi bi-exclamation-circle"></i>
                            Format: PDF, DOC, DOCX | Maksimal: 5MB
                        </small>
                        @error('file')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Info Tambahan -->
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-lightbulb-fill"></i> <strong>Tips:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Pastikan dokumen sudah dalam format yang benar</li>
                            <li>Berikan judul yang jelas dan deskriptif</li>
                            <li>Dosen akan memberikan feedback maksimal 3 hari kerja</li>
                        </ul>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-unej-primary">
                            <i class="bi bi-send-fill"></i> Kirim Bimbingan
                        </button>
                        <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
