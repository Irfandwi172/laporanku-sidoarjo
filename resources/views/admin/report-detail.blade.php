@extends('layouts.app')

@section('title', 'Detail Laporan #' . $report->id)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-file-text"></i> Detail Laporan #{{ $report->id }}</h2>
                <a href="{{ route('admin.reports') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Report Details -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Laporan</h5>
                    <span class="badge {{ $report->status_badge }} fs-6">{{ $report->status }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nama Pelapor:</strong><br>
                            {{ $report->nama_pelapor }}
                        </div>
                        <div class="col-md-6">
                            <strong>Nomor HP:</strong><br>
                            {{ $report->nomor_hp }}
                        </div>
                    </div>
                    
                    @if($report->email)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Email:</strong><br>
                            {{ $report->email }}
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong>Lokasi:</strong><br>
                        {{ $report->lokasi }}
                    </div>

                    <div class="mb-3">
                        <strong>Deskripsi Masalah:</strong><br>
                        <div class="border rounded p-3 bg-light">
                            {{ $report->deskripsi }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Tanggal Laporan:</strong><br>
                        {{ $report->created_at->format('d F Y, H:i') }} WIB
                    </div>

                    @if($report->foto)
                    <div class="mb-3">
                        <strong>Foto Pendukung:</strong><br>
                        <img src="{{ Storage::url($report->foto) }}" alt="Foto Laporan" class="img-fluid rounded mt-2" style="max-height: 400px;">
                    </div>
                    @endif

                    @if($report->status == 'Selesai')
                    <div class="alert alert-success">
                        <h6><i class="bi bi-check-circle"></i> Laporan Telah Selesai</h6>
                        <div class="row">
                            @if($report->tanggal_mulai_perbaikan)
                            <div class="col-md-6">
                                <strong>Tanggal Mulai:</strong> {{ $report->tanggal_mulai_perbaikan->format('d M Y') }}
                            </div>
                            @endif
                            @if($report->tanggal_selesai_perbaikan)
                            <div class="col-md-6">
                                <strong>Tanggal Selesai:</strong> {{ $report->tanggal_selesai_perbaikan->format('d M Y') }}
                            </div>
                            @endif
                            @if($report->durasi_penanganan)
                            <div class="col-12 mt-2">
                                <strong>Total Durasi:</strong> {{ $report->durasi_penanganan }} hari
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($report->catatan_admin)
                    <div class="alert alert-info">
                        <strong><i class="bi bi-chat-left-text"></i> Catatan Admin:</strong><br>
                        {{ $report->catatan_admin }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reports.update-status', $report) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" class="form-select" id="status" required>
                                <option value="Menunggu Verifikasi" {{ $report->status == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="Diverifikasi" {{ $report->status == 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                                <option value="Dalam Perbaikan" {{ $report->status == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                <option value="Selesai" {{ $report->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="estimasi_durasi" class="form-label">Estimasi Durasi (hari)</label>
                            <input type="number" class="form-control" id="estimasi_durasi" name="estimasi_durasi" 
                                   value="{{ old('estimasi_durasi', $report->estimasi_durasi) }}" min="1">
                        </div>

                        <div class="mb-3" id="tanggal_mulai_field" style="display: none;">
                            <label for="tanggal_mulai_perbaikan" class="form-label">Tanggal Mulai Perbaikan</label>
                            <input type="date" class="form-control" id="tanggal_mulai_perbaikan" name="tanggal_mulai_perbaikan" 
                                   value="{{ old('tanggal_mulai_perbaikan', $report->tanggal_mulai_perbaikan?->format('Y-m-d')) }}">
                        </div>

                        <div class="mb-3" id="tanggal_selesai_field" style="display: none;">
                            <label for="tanggal_selesai_perbaikan" class="form-label">Tanggal Selesai Perbaikan</label>
                            <input type="date" class="form-control" id="tanggal_selesai_perbaikan" name="tanggal_selesai_perbaikan" 
                                   value="{{ old('tanggal_selesai_perbaikan', $report->tanggal_selesai_perbaikan?->format('Y-m-d')) }}">
                        </div>

                        <div class="mb-3">
                            <label for="catatan_admin" class="form-label">Catatan Admin</label>
                            <textarea class="form-control" id="catatan_admin" name="catatan_admin" rows="3" 
                                      placeholder="Tambahkan catatan untuk pelapor (opsional)">{{ old('catatan_admin', $report->catatan_admin) }}</textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Progress Timeline -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Timeline Progress</h6>
                </div>
                <div class="card-body">
                    <div class="status-timeline">
                        <div class="status-item {{ $report->status == 'Menunggu Verifikasi' ? 'active' : 'completed' }}">
                            <strong>Menunggu Verifikasi</strong>
                            <br><small class="text-muted">{{ $report->created_at->format('d M Y') }}</small>
                        </div>
                        <div class="status-item {{ $report->status == 'Diverifikasi' ? 'active' : (in_array($report->status, ['Dalam Perbaikan', 'Selesai']) ? 'completed' : '') }}">
                            <strong>Diverifikasi</strong>
                            @if(in_array($report->status, ['Diverifikasi', 'Dalam Perbaikan', 'Selesai']))
                            <br><small class="text-muted">Verified</small>
                            @endif
                        </div>
                        <div class="status-item {{ $report->status == 'Dalam Perbaikan' ? 'active' : ($report->status == 'Selesai' ? 'completed' : '') }}">
                            <strong>Dalam Perbaikan</strong>
                            @if($report->tanggal_mulai_perbaikan)
                            <br><small class="text-muted">{{ $report->tanggal_mulai_perbaikan->format('d M Y') }}</small>
                            @endif
                        </div>
                        <div class="status-item {{ $report->status == 'Selesai' ? 'completed' : '' }}">
                            <strong>Selesai</strong>
                            @if($report->tanggal_selesai_perbaikan)
                            <br><small class="text-muted">{{ $report->tanggal_selesai_perbaikan->format('d M Y') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const tanggalMulaiField = document.getElementById('tanggal_mulai_field');
    const tanggalSelesaiField = document.getElementById('tanggal_selesai_field');

    function toggleDateFields() {
        const status = statusSelect.value;
        
        if (status === 'Dalam Perbaikan') {
            tanggalMulaiField.style.display = 'block';
            tanggalSelesaiField.style.display = 'none';
        } else if (status === 'Selesai') {
            tanggalMulaiField.style.display = 'block';
            tanggalSelesaiField.style.display = 'block';
        } else {
            tanggalMulaiField.style.display = 'none';
            tanggalSelesaiField.style.display = 'none';
        }
    }

    statusSelect.addEventListener('change', toggleDateFields);
    toggleDateFields(); // Initialize on page load
});
</script>
@endpush
