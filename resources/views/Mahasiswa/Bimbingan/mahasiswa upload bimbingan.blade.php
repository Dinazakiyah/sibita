@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Upload File Bimbingan</h3>

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

        {{-- Pilihan Dosen Pembimbing --}}
        <div class="mb-3">
            <label class="form-label">Pilih Dosen Pembimbing</label>
            <select name="dosen_id" class="form-control" required>
                <option value="">-- Pilih Dosen Pembimbing --</option>
                @foreach ($dosens as $dosen)
                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Judul --}}
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="judul" class="form-control" required
                   placeholder="Masukkan judul bimbingan">
        </div>

        {{-- Fase --}}
        <div class="mb-3">
            <label class="form-label">Fase</label>
            <input type="text" name="fase" class="form-control" required
                   placeholder="contoh: proposal, revisi bab 2, catatan">
        </div>

        {{-- File Upload --}}
        <div class="mb-3">
            <label class="form-label">Upload File</label>
            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.odt,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.oasis.opendocument.text">
                <small class="form-text text-muted d-block mt-2">Format: PDF, DOC, DOCX, atau ODT | Ukuran Maksimal: 10MB</small>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label class="form-label">Deskripsi (opsional)</label>
            <textarea name="deskripsi" class="form-control"
                      placeholder="Tambahkan catatan untuk dosen..."></textarea>
        </div>

        <button class="btn btn-primary">Upload Bimbingan</button>
    </form>
</div>
@endsection
