@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-speedometer2"></i> Dashboard Admin</h2>
                <a href="{{ route('admin.reports') }}" class="btn btn-primary">
                    <i class="bi bi-list"></i> Kelola Laporan
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

    <!-- Top 5 Prioritas SAW
    @if(isset($priority_reports) && $priority_reports->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle"></i> Top 5 Prioritas Perbaikan (Metode SAW)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Prioritas</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Skor SAW</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($priority_reports as $report)
                                <tr>
                                    <td>
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
                                    <td>{{ Str::limit($report->lokasi, 40) }}</td>
                                    <td><span class="badge {{ $report->status_badge }}">{{ $report->status }}</span></td>
                                    <td><strong class="text-success">{{ number_format($report->skor_saw, 4) }}</strong></td>
                                    <td>
                                        <a href="{{ route('admin.show', $report->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="{{ route('admin.prioritas') }}" class="btn btn-primary">
                            <i class="bi bi-list"></i> Lihat Semua Prioritas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif -->

    <!-- Recent Reports -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Laporan Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($recent_reports->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                    <td>#{{ $report->id }}</td>
                                    <td>{{ $report->nama_pelapor }}</td>
                                    <td>{{ Str::limit($report->lokasi, 30) }}</td>
                                    <td><span class="badge {{ $report->status_badge }}">{{ $report->status }}</span></td>
                                    <td>{{ $report->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.show', $report) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada laporan terbaru.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
}
</style>
@endsection