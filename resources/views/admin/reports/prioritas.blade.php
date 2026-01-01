@extends('layouts.app')

@section('title', 'Prioritas Perbaikan Jalan - Metode SAW')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-sort-down"></i> Prioritas Perbaikan (Metode SAW)</h2>
                <a href="{{ route('admin.reports') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="mb-3"><i class="bi bi-calculator"></i> Metode SAW (Simple Additive Weighting)</h5>
                    <p class="mb-2">Sistem menggunakan 4 kriteria untuk menentukan prioritas perbaikan jalan:</p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="badge bg-primary w-100 p-2 mb-2">
                                <strong>C1: Tingkat Kerusakan</strong><br>
                                Bobot: 40%
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="badge bg-success w-100 p-2 mb-2">
                                <strong>C2: Lokasi Strategis</strong><br>
                                Bobot: 30%
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="badge bg-warning text-dark w-100 p-2 mb-2">
                                <strong>C3: Jumlah Pengguna</strong><br>
                                Bobot: 20%
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="badge bg-info w-100 p-2 mb-2">
                                <strong>C4: Kedekatan Fasum</strong><br>
                                Bobot: 10%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Prioritas Perbaikan Jalan</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($reports->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="80" class="text-center">Prioritas</th>
                                    <th>ID</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th class="text-center">C1<br><small>Kerusakan</small></th>
                                    <th class="text-center">C2<br><small>Lokasi</small></th>
                                    <th class="text-center">C3<br><small>Pengguna</small></th>
                                    <th class="text-center">C4<br><small>Fasum</small></th>
                                    <th class="text-center">Skor SAW</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $report)
                                <tr>
                                    <td class="text-center">
                                        @if($report->prioritas == 1)
                                            <span class="badge bg-danger fs-6">🥇 {{ $report->prioritas }}</span>
                                        @elseif($report->prioritas == 2)
                                            <span class="badge bg-warning fs-6">🥈 {{ $report->prioritas }}</span>
                                        @elseif($report->prioritas == 3)
                                            <span class="badge bg-info fs-6">🥉 {{ $report->prioritas }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $report->prioritas }}</span>
                                        @endif
                                    </td>
                                    <td>#{{ $report->id }}</td>
                                    <td>{{ Str::limit($report->lokasi, 30) }}</td>
                                    <td><span class="badge {{ $report->status_badge }}">{{ $report->status }}</span></td>
                                    <td class="text-center"><span class="badge bg-primary">{{ $report->tingkat_kerusakan }}</span></td>
                                    <td class="text-center"><span class="badge bg-success">{{ $report->lokasi_strategis }}</span></td>
                                    <td class="text-center"><span class="badge bg-warning text-dark">{{ $report->jumlah_pengguna }}</span></td>
                                    <td class="text-center"><span class="badge bg-info">{{ $report->kedekatan_fasum }}</span></td>
                                    <td class="text-center"><strong class="text-success">{{ number_format($report->skor_saw, 4) }}</strong></td>
                                                                       <td>
                                        <a href="{{ route('admin.show', $report->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.edit', $report->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="text-center p-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="mt-2 text-muted">Belum ada data prioritas perbaikan yang tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
