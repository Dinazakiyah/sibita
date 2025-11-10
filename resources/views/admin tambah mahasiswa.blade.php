{{-- File: resources/views/admin/mahasiswa/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-plus-fill"></i> Tambah Mahasiswa Baru
            </div>
            <div class="card-body">
                <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                    @csrf

                    <!-- Nama -->
                    <div class="mb-3">
                        <label class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NIM -->
                    <div class="mb-3">
                        <label class="form-label">
                            NIM <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="nim_nip"
                               class="form-control @error('nim_nip') is-invalid @enderror"
                               value="{{ old('nim_nip') }}"
                               required>
                        @error('nim_nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div class="mb-3">
                        <label class="form-label">
                            Nomor HP
                        </label>
                        <input type="text"
                               name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">
                            Password <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>
                        <small class="text-muted">Minimal 6 karakter</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <!-- Dosen Pembimbing -->
                    <h5 class="mb-3"><i class="bi bi-person-badge"></i> Dosen Pembimbing</h5>

                    <div class="mb-3">
                        <label class="form-label">
                            Dosen Pembimbing 1 <span class="text-danger">*</span>
                        </label>
                        <select name="dosen_pembimbing_1"
                                class="form-select @error('dosen_pembimbing_1') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Dosen Pembimbing 1 --</option>
                            @foreach($dosen as $d)
                                <option value="{{ $d->id }}" {{ old('dosen_pembimbing_1') == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }} ({{ $d->nim_nip }})
                                </option>
                            @endforeach
                        </select>
                        @error('dosen_pembimbing_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Dosen Pembimbing 2 <span class="text-danger">*</span>
                        </label>
                        <select name="dosen_pembimbing_2"
                                class="form-select @error('dosen_pembimbing_2') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Dosen Pembimbing 2 --</option>
                            @foreach($dosen as $d)
                                <option value="{{ $d->id }}" {{ old('dosen_pembimbing_2') == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }} ({{ $d->nim_nip }})
                                </option>
                            @endforeach
                        </select>
                        @error('dosen_pembimbing_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-unej-success">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
