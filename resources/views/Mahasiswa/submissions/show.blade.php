@extends('layouts.app')

@section('title','Detail Submission')

@section('content')
<div class="card">
    <div class="card-header">Detail Submission</div>
    <div class="card-body">
        <h5>{{ $submission->file_name ?? '-' }}</h5>
        <p>Uploaded at: {{ $submission->submitted_at }}</p>
        <p>Status: {{ $submission->status }}</p>

        <h6 class="mt-3">Comments</h6>
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
