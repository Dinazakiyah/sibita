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

    <form action="{{ route('Mahasiswa.Bimbingan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Pilihan Dosen Pembimbing --}}
        <div class="mb-3">
            <label class="form-label">Pilih Dosen Pembimbing</label>
            <select name="dosen_id" class="form-control">
                <option value="">-- Pilih Dosen Pembimbing --</option>
                @foreach ($dosens as $dosen)
                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Jenis File --}}
        <div class="mb-3">
            <label class="form-label">Jenis File</label>
            <input type="text" name="jenis_file" class="form-control"
                   placeholder="contoh: proposal, revisi bab 2, catatan">
        </div>

        {{-- File Upload --}}
        <div class="mb-3">
            <label class="form-label">Upload File</label>
            <input type="file" name="file" class="form-control">
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
