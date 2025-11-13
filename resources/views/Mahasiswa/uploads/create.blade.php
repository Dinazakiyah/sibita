@extends('layouts.app')

@section('title','Upload Submission')

@section('content')
<div class="card">
    <div class="card-header">Upload File</div>
    <div class="card-body">
        <form action="{{ route('mahasiswa.uploads.store', $bimbingan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">File</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipe</label>
                <select name="file_type" class="form-control">
                    <option value="draft">Draft</option>
                    <option value="revision">Revision</option>
                    <option value="final">Final</option>
                </select>
            </div>
            <button class="btn btn-unej-primary">Upload</button>
        </form>
    </div>
</div>
@endsection
