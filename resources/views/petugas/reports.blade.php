@extends('layouts.petugas')

@section('title', 'Kelola Laporan')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-list-ul"></i> Kelola Laporan</h2>
                <div>
                    <form action="{{ route('petugas.hitung-prioritas') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-calculator"></i> Hitung Prioritas SAW
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('petugas.reports') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Filter Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="Diverifikasi" {{ request('status') == 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                        <option value="Dalam Perbaikan" {{ request('status') == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari lokasi, pelapor, atau deskripsi...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card shadow">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Laporan ({{ $reports->total() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($reports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Pelapor</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Prioritas SAW</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td><strong>#{{ $report->id }}</strong></td>
                                <td>
                                    <div>{{ $report->nama_pelapor }}</div>
                                    <small class="text-muted">{{ $report->nomor_hp }}</small>
                                </td>
                                <td>{{ Str::limit($report->lokasi, 40) }}</td>
                                <td><span class="badge {{ $report->status_badge }}">{{ $report->status }}</span></td>
                                <td class="text-center">
                                    @if($report->prioritas)
                                        @if($report->prioritas <= 3)
                                            <span class="badge bg-danger fs-6">{{ $report->prioritas }}</span>
                                        @elseif($report->prioritas <= 10)
                                            <span class="badge bg-warning fs-6">{{ $report->prioritas }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $report->prioritas }}</span>
                                        @endif
                                        <div><small class="text-muted">{{ number_format($report->skor_saw, 3) }}</small></div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $report->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('petugas.show', $report->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $reports->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">Tidak ada laporan ditemukan</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection