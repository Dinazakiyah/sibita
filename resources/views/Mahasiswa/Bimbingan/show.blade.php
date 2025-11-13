@extends('layouts.app')

@section('title','Detail Bimbingan (Mahasiswa)')

@section('content')
<div class="card">
    <div class="card-header">Detail Bimbingan</div>
    <div class="card-body">
        <h5>{{ $bimbingan->judul ?? '-' }}</h5>
        <p>Dosen: {{ $bimbingan->dosen->name ?? '-' }}</p>

        <h6 class="mt-3">Submissions</h6>
        @if(isset($submissions) && $submissions->count())
            <ul>
                @foreach($submissions as $s)
                    <li>{{ $s->file_name }} - {{ $s->status }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Belum ada submission.</p>
        @endif
    </div>
</div>
@endsection
