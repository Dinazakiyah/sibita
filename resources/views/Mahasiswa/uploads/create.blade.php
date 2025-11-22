@extends('layouts.app')

@section('title', 'Upload Bimbingan Baru')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: var(--unej-red);">
                    <h5 class="mb-0">
                        <i class="bi bi-upload"></i> Upload Bimbingan Baru
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Fase Aktif Info -->
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Fase Bimbingan Saat Ini:</strong>
                                <span class="badge bg-primary ms-2">{{ strtoupper($faseAktif) }}</span>
                                <br>
                                <small class="text-muted">
                                    @if($faseAktif == 'sempro')
                                        Upload dokumen proposal untuk seminar proposal
                                    @else
                                        Upload dokumen skripsi untuk sidang akhir
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('mahasiswa.bimbingan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Pilih Dosen Pembimbing -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person-badge"></i> Pilih Dosen Pembimbing
                            </label>
                            <select name="dosen_id" class="form-select" required>
                                <option value="">-- Pilih Dosen Pembimbing --</option>
                                @foreach($dosenPembimbing as $dosen)
                                    <option value="{{ $dosen->id }}">
                                        {{ $dosen->name }} ({{ $dosen->pivot->jenis_pembimbing == 'pembimbing_1' ? 'Pembimbing 1' : 'Pembimbing 2' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('dosen_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Judul Bimbingan -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-file-earmark-text"></i> Judul Bimbingan
                            </label>
                            <input type="text" name="judul" class="form-control" placeholder="Masukkan judul bimbingan Anda" required>
                            @error('judul')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-chat-left-text"></i> Deskripsi (Opsional)
                            </label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan isi bimbingan atau catatan tambahan"></textarea>
                            @error('deskripsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload File -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-file-earmark-arrow-up"></i> Upload File
                            </label>
                            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx" required>
                            <div class="form-text">
                                <small class="text-muted">
                                    Format yang didukung: PDF, DOC, DOCX. Maksimal 5MB.
                                </small>
                            </div>
                            @error('file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-unej-primary">
                                <i class="bi bi-upload"></i> Upload Bimbingan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Progress Info -->
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-graph-up-arrow"></i> Status Progres Anda
                    </h6>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-3 rounded" style="background: linear-gradient(135deg, var(--unej-red), var(--unej-yellow)); color: white;">
                                <div class="h4 mb-1">{{ $status->getProgresPercentage() }}%</div>
                                <small>Progres Keseluruhan</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded bg-light">
                                <div class="h4 mb-1 text-{{ $status->layak_sempro ? 'success' : 'secondary' }}">
                                    <i class="bi bi-{{ $status->layak_sempro ? 'check-circle-fill' : 'circle' }}"></i>
                                </div>
                                <small>Layak Sempro</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded bg-light">
                                <div class="h4 mb-1 text-{{ $status->layak_sidang ? 'success' : 'secondary' }}">
                                    <i class="bi bi-{{ $status->layak_sidang ? 'trophy-fill' : 'circle' }}"></i>
                                </div>
                                <small>Layak Sidang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
