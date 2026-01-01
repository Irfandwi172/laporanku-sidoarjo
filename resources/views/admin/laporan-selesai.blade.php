@extends('layouts.app')

@section('title', 'Laporan Selesai')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-check-circle"></i> Laporan Selesai</h2>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.laporan-selesai') }}">
                    <div class="row g-3">
                        <div class="col-md-10">
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
                                    <th>Pelapor</th>
                                    <th>Lokasi</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Durasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $report)
                                    <tr>
                                        <td>#{{ $report->id }}</td>
                                        <td>
                                            <strong>{{ $report->nama_pelapor }}</strong><br>
                                            <small class="text-muted">{{ $report->nomor_hp }}</small>
                                        </td>
                                        <td>{{ Str::limit($report->lokasi, 40) }}</td>
                                        <td>
                                            @if($report->tanggal_selesai_perbaikan)
                                                {{ $report->tanggal_selesai_perbaikan->format('d M Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($report->durasi_penanganan)
                                                <span class="badge bg-info">{{ $report->durasi_penanganan }} hari</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.show', $report) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            <a href="{{ route('admin.reports.download-pdf', $report->id) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="bi bi-download"></i> PDF
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
                        <h4 class="text-muted">Belum ada laporan yang selesai</h4>
                        <p class="text-muted">Laporan yang sudah selesai akan muncul di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection