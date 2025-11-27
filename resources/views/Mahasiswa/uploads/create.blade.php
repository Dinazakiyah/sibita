@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-4">Upload File Bimbingan</h2>

    {{-- ERROR MESSAGE --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mahasiswa.bimbingan.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- PILIH DOSEN PEMBIMBING --}}
    <div class="form-group">
        <label class="font-semibold">Pilih Dosen Pembimbing</label>
        <select name="bimbingan_id" class="form-control">
            <option value="">-- Pilih Dosen Pembimbing --</option>

            @foreach ($dosenPembimbing as $dosen)
                <option value="{{ $dosen->pivot->id }}">
                    {{ $dosen->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- FILE TYPE --}}
    <div class="form-group mt-3">
        <label class="font-semibold">Jenis File</label>
        <input type="text" name="file_type" class="form-control"
               placeholder="contoh: proposal, revisi bab 2, catatan" required>
    </div>

    {{-- FILE UPLOAD --}}
    <div class="form-group mt-3">
        <label class="font-semibold">Upload File</label>
        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.odt,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.oasis.opendocument.text" required>
        <small class="form-text text-muted d-block mt-2">Format: PDF, DOC, DOCX, atau ODT | Ukuran Maksimal: 10MB</small>
    </div>

    {{-- DESCRIPTION --}}
    <div class="form-group mt-3">
        <label class="font-semibold">Deskripsi (opsional)</label>
        <textarea name="description" class="form-control" rows="3"
                  placeholder="Tambahkan catatan untuk dosen..."></textarea>
    </div>

    <button type="submit" class="btn btn-danger mt-4">
        Upload Bimbingan
    </button>
</form>


</div>
@endsection
