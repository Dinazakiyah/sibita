@extends('layouts.app')

@section('title','Review Submission')

@section('content')
<div class="card">
    <div class="card-header">Review Submission</div>
    <div class="card-body">
        <h5>{{ $submission->file_name ?? 'File' }}</h5>
        <p>Mahasiswa: {{ $submission->mahasiswa->name ?? '-' }}</p>
        <p>Tipe: {{ $submission->file_type ?? '-' }}</p>
        <p>Status: {{ $submission->status ?? '-' }}</p>

        <h6 class="mt-3">Komentar</h6>
        @forelse($comments as $c)
            <div class="border p-2 mb-2">
                <strong>{{ $c->dosen->name ?? 'Dosen' }}</strong>
                <p>{{ $c->comment }}</p>
            </div>
        @empty
            <p class="text-muted">Belum ada komentar</p>
        @endforelse
    </div>
</div>
@endsection
