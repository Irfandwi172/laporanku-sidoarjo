@extends('layouts.app')

@section('title', 'Status Laporan')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-list-check"></i> Status Laporan Anda</h2>
                <a href="{{ route('reports.check') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Cek Laporan Lain
                </a>
            </div>
            
            @if($reports->count() > 0)
                @foreach($reports as $report)
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Laporan #{{ $report->id }}</h5>
                        <span class="badge {{ $report->status_badge }} fs-6">{{ $report->status }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <strong>Pelapor:</strong> {{ $report->nama_pelapor }}
                                </div>
                                <div class="mb-3">
                                    <strong>Lokasi:</strong> {{ $report->lokasi }}
                                </div>
                                <div class="mb-3">
                                    <strong>Deskripsi:</strong> 
                                    <div class="border rounded p-2 bg-light mt-1">
                                        {{ $report->deskripsi }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <strong>Tanggal Laporan:</strong> {{ $report->created_at->format('d F Y, H:i') }} WIB
                                </div>

                                @if($report->foto)
                                <div class="mb-3">
                                    <strong>Foto Pendukung:</strong><br>
                                    <img src="{{ Storage::url($report->foto) }}" alt="Foto Laporan" class="img-thumbnail mt-2" style="max-height: 300px;">
                                </div>
                                @endif

                                <hr>

                                <!-- ALERT UNTUK LAPORAN DITOLAK -->
                                @if($report->status == 'Ditolak')
                                <div class="alert alert-danger">
                                    <h5 class="alert-heading">
                                        <i class="bi bi-x-circle-fill"></i> Laporan Ditolak
                                    </h5>
                                    <hr>
                                    <p class="mb-2"><strong>Alasan Penolakan:</strong></p>
                                    <div class="bg-white border-start border-danger border-4 rounded p-3 mb-3">
                                        {{ $report->alasan_penolakan ?? 'Tidak ada keterangan alasan penolakan.' }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> Ditolak pada: {{ $report->updated_at->format('d F Y, H:i') }} WIB
                                    </small>
                                    <hr>
                                    <p class="mb-0">
                                        <i class="bi bi-info-circle"></i> Anda dapat mengajukan laporan baru jika diperlukan.
                                    </p>
                                    <a href="{{ route('reports.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="bi bi-plus-circle"></i> Buat Laporan Baru
                                    </a>
                                </div>
                                @endif
                                
                                <!-- ALERT UNTUK LAPORAN SELESAI -->
                                @if($report->status == 'Selesai')
                                <div class="alert alert-success">
                                    <h6><i class="bi bi-check-circle-fill"></i> Laporan Selesai Ditangani</h6>
                                    <hr>
                                    <div class="row">
                                        @if($report->tanggal_mulai_perbaikan)
                                        <div class="col-md-6">
                                            <small><strong>Tanggal Mulai:</strong> {{ $report->tanggal_mulai_perbaikan->format('d M Y') }}</small>
                                        </div>
                                        @endif
                                        @if($report->tanggal_selesai_perbaikan)
                                        <div class="col-md-6">
                                            <small><strong>Tanggal Selesai:</strong> {{ $report->tanggal_selesai_perbaikan->format('d M Y') }}</small>
                                        </div>
                                        @endif
                                        @if($report->durasi_penanganan)
                                        <div class="col-12 mt-2">
                                            <small><strong>Durasi Penanganan:</strong> {{ $report->durasi_penanganan }} hari</small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <!-- ESTIMASI DURASI -->
                                @if($report->estimasi_durasi && in_array($report->status, ['Diverifikasi', 'Dalam Perbaikan']))
                                <div class="alert alert-info">
                                    <i class="bi bi-clock"></i> <strong>Estimasi Penanganan:</strong> {{ $report->estimasi_durasi }} hari kerja
                                </div>
                                @endif

                                <!-- CATATAN ADMIN (hanya jika tidak ditolak) -->
                                @if($report->catatan_admin && $report->status != 'Ditolak')
                                <div class="alert alert-warning">
                                    <h6><i class="bi bi-chat-left-text"></i> Catatan dari Admin</h6>
                                    <hr>
                                    <p class="mb-0">{{ $report->catatan_admin }}</p>
                                </div>
                                @endif

                                <!-- INFO PRIORITAS SAW -->
                                @if($report->prioritas && in_array($report->status, ['Diverifikasi', 'Dalam Perbaikan']))
                                <div class="alert alert-primary">
                                    <h6><i class="bi bi-star-fill"></i> Informasi Prioritas</h6>
                                    <hr>
                                    <p class="mb-0">
                                        Laporan Anda berada di urutan prioritas ke-<strong>{{ $report->prioritas }}</strong> 
                                        dari sistem penilaian perbaikan jalan.
                                    </p>
                                    @if($report->prioritas <= 5)
                                    <p class="mb-0 mt-2">
                                        <span class="badge bg-success">Prioritas Tinggi</span> - Akan segera ditangani
                                    </p>
                                    @elseif($report->prioritas <= 10)
                                    <p class="mb-0 mt-2">
                                        <span class="badge bg-info">Prioritas Sedang</span>
                                    </p>
                                    @endif
                                </div>
                                @endif
                            </div>

                            <!-- TIMELINE (hanya jika tidak ditolak) -->
                            @if($report->status != 'Ditolak')
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3">Progress Penanganan:</h6>
                                        <div class="status-timeline">
                                            <div class="status-item {{ $report->status == 'Menunggu Verifikasi' ? 'active' : 'completed' }}">
                                                <strong>1. Menunggu Verifikasi</strong>
                                                <br><small class="text-muted">{{ $report->created_at->format('d M Y') }}</small>
                                                @if($report->status == 'Menunggu Verifikasi')
                                                <br><small class="text-primary">Dalam proses verifikasi</small>
                                                @endif
                                            </div>
                                            <div class="status-item {{ $report->status == 'Diverifikasi' ? 'active' : (in_array($report->status, ['Dalam Perbaikan', 'Selesai']) ? 'completed' : '') }}">
                                                <strong>2. Diverifikasi</strong>
                                                @if(in_array($report->status, ['Diverifikasi', 'Dalam Perbaikan', 'Selesai']))
                                                <br><small class="text-muted">Verified ✓</small>
                                                @endif
                                                @if($report->status == 'Diverifikasi')
                                                <br><small class="text-primary">Menunggu perbaikan</small>
                                                @endif
                                            </div>
                                            <div class="status-item {{ $report->status == 'Dalam Perbaikan' ? 'active' : ($report->status == 'Selesai' ? 'completed' : '') }}">
                                                <strong>3. Dalam Perbaikan</strong>
                                                @if($report->tanggal_mulai_perbaikan)
                                                <br><small class="text-muted">{{ $report->tanggal_mulai_perbaikan->format('d M Y') }}</small>
                                                @endif
                                                @if($report->status == 'Dalam Perbaikan')
                                                <br><small class="text-primary">Sedang dikerjakan</small>
                                                @endif
                                            </div>
                                            <div class="status-item {{ $report->status == 'Selesai' ? 'completed' : '' }}">
                                                <strong>4. Selesai</strong>
                                                @if($report->tanggal_selesai_perbaikan)
                                                <br><small class="text-muted">{{ $report->tanggal_selesai_perbaikan->format('d M Y') }}</small>
                                                @endif
                                                @if($report->status == 'Selesai')
                                                <br><small class="text-success">Perbaikan selesai ✓</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Summary Info -->
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <p class="mb-0">
                            <i class="bi bi-info-circle"></i> 
                            Ditemukan <strong>{{ $reports->count() }}</strong> laporan dengan nomor HP ini.
                        </p>
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-center">
                    <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                    <h4>Laporan Tidak Ditemukan</h4>
                    <p>Tidak ada laporan yang ditemukan dengan nomor HP tersebut.</p>
                    <a href="{{ route('reports.check') }}" class="btn btn-primary">Coba Lagi</a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.status-timeline {
    position: relative;
    padding-left: 1.5rem;
}

.status-timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0.5rem;
    bottom: 0.5rem;
    width: 2px;
    background: #dee2e6;
}

.status-item {
    position: relative;
    padding-bottom: 1.5rem;
    color: #6c757d;
}

.status-item:last-child {
    padding-bottom: 0;
}

.status-item::before {
    content: '';
    position: absolute;
    left: -1.25rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border: 2px solid #dee2e6;
    border-radius: 50%;
    background: white;
    z-index: 1;
}

.status-item.active::before {
    border-color: #007bff;
    background: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
}

.status-item.completed::before {
    border-color: #28a745;
    background: #28a745;
}

.status-item.active,
.status-item.completed {
    color: #212529;
}
</style>
@endsection