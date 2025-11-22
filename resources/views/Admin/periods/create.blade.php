@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Tambah Periode Bimbingan</h4>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form action="{{ route('admin.periods.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nama Periode</label>
                        <input type="text" name="period_name"
                               class="form-control" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="description"></textarea>
                </div>

                <button class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
