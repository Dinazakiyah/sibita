@extends('layouts.app')

@section('title','Daftar Bimbingan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-file-earmark-arrow-up"></i> Ajukan Bimbingan</h2>
</div>

<!-- Form Upload Bimbingan -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-cloud-upload"></i> Upload File Bimbingan</h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mahasiswa.bimbingan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Pilih Bimbingan (jika ada multiple bimbingan) -->
            <div class="mb-3">
                <label class="form-label">
                    Topik / Judul Bimbingan <span class="text-danger">*</span>
                </label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                       placeholder="Contoh: Sistem Informasi Manajemen Inventori"
                       value="{{ old('judul') }}" required>
                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label class="form-label">Deskripsi / Catatan</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                          rows="3" placeholder="Jelaskan ringkas tentang bimbingan ini...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tipe File -->
            <div class="mb-3">
                <label class="form-label">
                    Tipe File <span class="text-danger">*</span>
                </label>
                <select name="file_type" class="form-control @error('file_type') is-invalid @enderror" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="draft" {{ old('file_type') === 'draft' ? 'selected' : '' }}>
                        📝 Draft (Konsep Awal)
                    </option>
                    <option value="revision" {{ old('file_type') === 'revision' ? 'selected' : '' }}>
                        ✏️ Revisi (Perbaikan)
                    </option>
                    <option value="final" {{ old('file_type') === 'final' ? 'selected' : '' }}>
                        ✅ Final (Siap Sempro)
                    </option>
                </select>
                @error('file_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Upload File -->
            <div class="mb-4">
                <label class="form-label">
                    File Dokumen <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="file" name="file"
                               class="form-control @error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.odt,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.oasis.opendocument.text"
                           required>
                    <span class="input-group-text">
                        <i class="bi bi-paperclip"></i>
                    </span>
                </div>
                <small class="form-text text-muted d-block mt-2">
                    Format: PDF, DOC, DOCX, atau ODT (OpenDocument Text) | Ukuran Maksimal: 10MB
                </small>
                @error('file')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-unej-primary">
                    <i class="bi bi-cloud-upload"></i> Upload Bimbingan
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Riwayat Upload -->
@if(isset($bimbingan) && $bimbingan->count() > 0)
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Upload Anda</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal Upload</th>
                        <th>Nama File</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bimbingan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <small>{{ $item->created_at->format('d M Y') }}</small><br>
                                <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ Str::limit($item->file_name ?? $item->judul, 30) }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($item->file_type ?? 'draft') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark">Menunggu Review</span>
                            </td>
                            <td>
                                <a href="{{ route('mahasiswa.submissions.show', $item->id) }}"
                                   class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection
