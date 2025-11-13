@extends('layouts.app')

@section('title', 'Detail Bimbingan')

@section('content')
<div class="card">
    <div class="card-header">Detail Bimbingan</div>
    <div class="card-body">
        <h5>{{ $bimbingan->judul ?? 'Judul tidak tersedia' }}</h5>
        <p>Mahasiswa: {{ $bimbingan->mahasiswa->name ?? '-' }}</p>

        <h6 class="mt-4">Submissions</h6>
        @if(isset($submissions) && $submissions->count())
            <ul>
                @foreach($submissions as $s)
                    <li>
                        {{ $s->file_name }} - Status: {{ $s->status }}
                        <a href="{{ route('dosen.submissions.review', $s->id) }}">Review</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Belum ada submission.</p>
        @endif
    </div>
</div>
@endsection
