@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-speedometer2"></i> Dashboard Petugas Lapangan</h2>
                    <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name }}</p>
                </div>
                <a href="{{ route('petugas.reports') }}" class="btn btn-primary">
                    <i class="bi bi-list-ul"></i> Lihat Semua Laporan
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
   <div class="row g-4 mb-4">
        <div class="col-md-2">
            <div class="card card-stats shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="text-primary">{{ $stats['total'] }}</h3>
                            <p class="card-category text-muted mb-0">Total Laporan</p>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-file-text" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm" style="border-left: 4px solid #ffc107;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="text-warning">{{ $stats['menunggu_verifikasi'] }}</h3>
                            <p class="card-category text-muted mb-0">Menunggu</p>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-clock" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm" style="border-left: 4px solid #17a2b8;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="text-info">{{ $stats['diverifikasi'] }}</h3>
                            <p class="card-category text-muted mb-0">Diverifikasi</p>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm" style="border-left: 4px solid #007bff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="text-primary">{{ $stats['dalam_perbaikan'] }}</h3>
                            <p class="card-category text-muted mb-0">Perbaikan</p>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-tools" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm" style="border-left: 4px solid #28a745;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="text-success">{{ $stats['selesai'] }}</h3>
                            <p class="card-category text-muted mb-0">Selesai</p>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm" style="border-left: 4px solid #dc3545;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="text-danger">{{ $stats['ditolak'] }}</h3>
                            <p class="card-category text-muted mb-0">Ditolak</p>
                        </div>
                        <div class="text-danger">
                            <i class="bi bi-x-circle" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock"></i> Laporan Terbaru</h5>
                    <a href="{{ route('petugas.reports') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($recent_reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Pelapor</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_reports as $report)
                                    <tr>
                                        <td><strong>#{{ $report->id }}</strong></td>
                                        <td>{{ $report->nama_pelapor }}</td>
                                        <td>{{ Str::limit($report->lokasi, 30) }}</td>
                                        <td><span class="badge {{ $report->status_badge }}">{{ $report->status }}</span></td>
                                        <td>{{ $report->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('petugas.show', $report->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Belum ada laporan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection