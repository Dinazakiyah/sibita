@extends('layouts.app')

@section('title', 'Riwayat Bimbingan (Dosen)')

@section('content')
<div class="card">
    <div class="card-header">Riwayat Bimbingan</div>
    <div class="card-body">
        @if(isset($bimbingan) && $bimbingan->count())
            <ul>
                @foreach($bimbingan as $b)
                    <li>{{ $b->judul }} ({{ $b->mahasiswa->name ?? '-' }}) - {{ $b->status }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Belum ada riwayat.</p>
        @endif
    </div>
</div>
@endsection
