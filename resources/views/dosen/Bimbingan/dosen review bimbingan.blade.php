{{-- File: resources/views/dosen/bimbingan/review.blade.php --}}
@extends('layouts.app')

@section('title', 'Review Bimbingan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-eye-fill"></i> Review Bimbingan Mahasiswa
            </div>
            <div class="card-body">
                <!-- Info Mahasiswa -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5><i class="bi bi-person-fill"></i> {{ $bimbingan->mahasiswa->name }}</h5>
                        <p class="text-muted mb-1">
                            <i class="bi bi-card-text"></i> NIM: {{ $bimbingan->mahasiswa->nim_nip }}
                        </p>
                        <p class="text-muted mb-0">
                            <i class="bi bi-envelope"></i> {{ $bimbingan->mahasiswa->email }}
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="badge {{ $bimbingan->fase == 'sempro' ? 'bg-info' : 'bg-primary' }}" style="font-size: 1rem;">
                            FASE: {{ strtoupper($bimbingan->fase) }}
                        </span>
                        <br>
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-calendar"></i>
                            Diupload: {{ $bimbingan->tanggal_upload->format('d F Y, H:i') }}
                        </small>
                    </div>
                </div>

                <hr>

                <!-- Detail Bimbingan -->
                <div class="mb-4">
                    <h6 class="mb-3"><i class="bi bi-file-text-fill"></i> Detail Bimbingan</h6>

                    <div class="mb-3">
                        <label class="fw-bold">Judul:</label>
                        <p>{{ $bimbingan->judul }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Deskripsi dari Mahasiswa:</label>
                        <p class="text-muted">
                            {{ $bimbingan->deskripsi ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Dokumen Mahasiswa:</label>
                        <div>
                            @if($bimbingan->submissionFiles && $bimbingan->submissionFiles->count() > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama File</th>
                                            <th>Tipe File</th>
                                            <th>Ukuran</th>
                                            <th>Unduh</th>
                                            <th>Komentar Dosen</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bimbingan->submissionFiles as $submission)
                                            <tr>
                                                <td>{{ $submission->file_name }}</td>
                                                <td>{{ ucfirst($submission->file_type) }}</td>
                                                <td>{{ number_format($submission->file_size / 1024, 2) }} KB</td>
                                                <td>
                                                    <a href="{{ asset('storage/' . $submission->file_path) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                                        <i class="bi bi-download"></i> Download
                                                    </a>
                                                </td>
                                                <td>
                                                    @php
                                                        $latestComment = $submission->comments->sortByDesc('created_at')->first();
                                                    @endphp
                                                    @if($latestComment)
                                                        <div>{{ $latestComment->comment }}</div>
                                                        <small class="text-muted">By {{ $latestComment->dosen->name ?? 'Dosen' }} at {{ $latestComment->created_at->format('d M Y, H:i') }}</small>
                                                    @else
                                                        <em>Tidak ada komentar</em>
                                                    @endif
                                                </td>
                                                <td>
<form action="{{ route('dosen.bimbingan.comment-submission', $submission->id) }}" method="POST">
                                                        @csrf
                                                        <div class="input-group">
                                                            <input type="text" name="comment" class="form-control form-control-sm" placeholder="Tambahkan komentar" required>
                                                            <button class="btn btn-primary btn-sm" type="submit">
                                                                <i class="bi bi-chat-text"></i>
                                                            </button>
                                                        </div>
                                                        @error('comment')
                                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p><em>Belum ada dokumen dari mahasiswa.</em></p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Status Saat Ini:</label>
                        <span class="{{ $bimbingan->getStatusBadge() }}">
                            {{ $bimbingan->getStatusText() }}
                        </span>
                    </div>
                </div>

                <hr>

                <!-- Form Review -->
                <form action="{{ route('dosen.bimbingan.submit-review', $bimbingan->id) }}" method="POST">
                    @csrf

                    <h6 class="mb-3"><i class="bi bi-chat-left-text-fill"></i> Berikan Review</h6>

                    <!-- Komentar -->
                    <div class="mb-3">
                        <label class="form-label">
                            Komentar / Saran / Revisi <span class="text-danger">*</span>
                        </label>
                        <textarea name="komentar"
                                  class="form-control"
                                  rows="6"
                                  placeholder="Tulis komentar, saran perbaikan, atau revisi yang diperlukan..."
                                  required>{{ old('komentar', $bimbingan->komentar_dosen) }}</textarea>
                        @error('komentar')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Persentase -->
                    <div class="mb-3">
                        <label class="form-label">
                            Persentase Penilaian (0-100%)
                        </label>
                        <input type="number"
                               name="percentage"
                               class="form-control"
                               min="0"
                               max="100"
                               step="0.01"
                               placeholder="Masukkan persentase penilaian..."
                               value="{{ old('percentage', $bimbingan->percentage) }}">
                        <small class="text-muted">Opsional: Masukkan nilai persentase untuk penilaian bimbingan ini</small>
                        @error('percentage')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label">
                            Keputusan <span class="text-danger">*</span>
                        </label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check p-3 border rounded bg-light">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="status"
                                           id="statusRevisi"
                                           value="revisi"
                                           required>
                                    <label class="form-check-label w-100" for="statusRevisi">
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                        <strong>Perlu Revisi</strong>
                                        <small class="d-block text-muted">Mahasiswa perlu memperbaiki dokumen</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check p-3 border rounded bg-light">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="status"
                                           id="statusApproved"
                                           value="approved"
                                           required>
                                    <label class="form-check-label w-100" for="statusApproved">
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                        <strong>Disetujui</strong>
                                        <small class="d-block text-muted">Dokumen sudah baik</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('status')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-unej-success">
                            <i class="bi bi-send-check-fill"></i> Kirim Review
                        </button>
                        <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
