@extends('layouts.app')

@section('title', 'Kelola Laporan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-list-ul"></i> Kelola Laporan</h2>
                <div>
                    <a href="{{ route('admin.prioritas') }}" class="btn btn-warning">
                        <i class="bi bi-sort-down"></i> Lihat Prioritas SAW
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports') }}">
                <div class="row g-3">
                   <div class="col-md-4">
    <label for="status" class="form-label">Filter Status</label>
    <select name="status" class="form-select" id="status">
        <option value="">Semua Status (Aktif)</option>
        <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
        <option value="Diverifikasi" {{ request('status') == 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
        <option value="Dalam Perbaikan" {{ request('status') == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
    </select>
    <small class="text-muted">Laporan Selesai dan Ditolak ada di menu terpisah</small>
</div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" name="search" class="form-control" id="search" 
                               value="{{ request('search') }}" placeholder="Cari nama, lokasi, atau deskripsi...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card shadow">
        <div class="card-body">
            @if($reports->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Prioritas</th>
                            <th>Pelapor</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td>#{{ $report->id }}</td>
                            <td>
                                @if($report->prioritas)
                                    @if($report->prioritas == 1)
                                        <span class="badge bg-danger">🥇 {{ $report->prioritas }}</span>
                                    @elseif($report->prioritas == 2)
                                        <span class="badge bg-warning text-dark">🥈 {{ $report->prioritas }}</span>
                                    @elseif($report->prioritas == 3)
                                        <span class="badge bg-info">🥉 {{ $report->prioritas }}</span>
                                    @elseif($report->prioritas <= 10)
                                        <span class="badge bg-primary">{{ $report->prioritas }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $report->prioritas }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $report->nama_pelapor }}</strong><br>
                                <small class="text-muted">{{ $report->nomor_hp }}</small>
                            </td>
                            <td>{{ Str::limit($report->lokasi, 40) }}</td>
                            <td><span class="badge {{ $report->status_badge }}">{{ $report->status }}</span></td>
                            <td>{{ $report->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.show', $report) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <a href="{{ route('admin.edit', $report) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $reports->withQueryString()->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted">Tidak ada laporan ditemukan</h4>
                <p class="text-muted">Coba ubah filter atau kata kunci pencarian Anda.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection