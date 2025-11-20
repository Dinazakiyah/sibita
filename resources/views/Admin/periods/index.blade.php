@extends('layouts.Admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h4>Daftar Periode Bimbingan</h4>
        <a href="{{ route('Admin.periods.create') }}"
           class="btn btn-primary">Tambah Periode</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nama Periode</th>
                        <th>Mulai</th>
                        <th>Akhir</th>
                        <th>Aktif?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($periods as $period)
                        <tr>
                            <td>{{ $period->period_name }}</td>
                            <td>{{ $period->start_date }}</td>
                            <td>{{ $period->end_date }}</td>
                            <td>
                                <span class="badge bg-{{ $period->is_active ? 'success' : 'secondary' }}">
                                    {{ $period->is_active ? 'Aktif' : 'Tidak' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $periods->links() }}
        </div>
    </div>
</div>
@endsection
