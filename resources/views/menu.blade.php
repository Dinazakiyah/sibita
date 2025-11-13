@extends('layouts.app')

@section('title', 'Menu Aplikasi')

@section('content')
<div class="card">
    <div class="card-header">
        Menu Cepat
    </div>
    <div class="card-body">
        <h5>Halo, {{ auth()->user()->name }}</h5>
        <p class="text-muted">Pilih aksi sesuai peran Anda.</p>

        @if(auth()->user()->isAdmin())
            <h6>Admin Prodi</h6>
            <div class="list-group mb-3">
                <a href="{{ route('admin.mahasiswa.index') }}" class="list-group-item list-group-item-action">Kelola Mahasiswa</a>
                <a href="{{ route('admin.dosen.index') }}" class="list-group-item list-group-item-action">Kelola Dosen</a>
                <a href="{{ route('admin.laporan') }}" class="list-group-item list-group-item-action">Laporan Aktivitas</a>
                <a href="{{ route('admin.periods') }}" class="list-group-item list-group-item-action">Kelola Jadwal / Periode</a>
            </div>
        @elseif(auth()->user()->isDosen())
            <h6>Dosen Pembimbing</h6>
            <div class="list-group mb-3">
                <a href="{{ route('dosen.mahasiswa.index') }}" class="list-group-item list-group-item-action">Daftar Mahasiswa Bimbingan</a>
                <a href="{{ route('dosen.dashboard') }}" class="list-group-item list-group-item-action">Lihat Submissions Pending</a>
                <a href="{{ route('dosen.history') }}" class="list-group-item list-group-item-action">Riwayat Bimbingan</a>
            </div>
        @elseif(auth()->user()->isMahasiswa())
            <h6>Mahasiswa</h6>
            <div class="list-group mb-3">
                <a href="{{ route('mahasiswa.bimbingan.index') }}" class="list-group-item list-group-item-action">Daftar Bimbingan</a>
                <a href="{{ route('mahasiswa.bimbingan.create') }}" class="list-group-item list-group-item-action">Ajukan Bimbingan (Upload)</a>
                <a href="{{ route('mahasiswa.progress') }}" class="list-group-item list-group-item-action">Lihat Progress</a>
                <a href="{{ route('mahasiswa.bimbingan.index') }}" class="list-group-item list-group-item-action">Unduh Riwayat / Arsip (pilih bimbingan)</a>
            </div>
        @endif

    </div>
</div>
@endsection
