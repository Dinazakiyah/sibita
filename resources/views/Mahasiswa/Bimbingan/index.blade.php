@extends('layouts.app')

@section('title','Daftar Bimbingan')

@section('content')
<div class="card">
    <div class="card-header">Daftar Bimbingan</div>
    <div class="card-body">
        @if(isset($bimbingan) && $bimbingan->count())
            <ul>
                @foreach($bimbingan as $b)
                    <li>
                        <a href="{{ route('mahasiswa.bimbingan.show', $b->id) }}">{{ $b->judul }}</a>
                        - Dosen: {{ $b->dosen->name ?? '-' }}
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Belum ada bimbingan.</p>
        @endif
    </div>
</div>
@endsection
