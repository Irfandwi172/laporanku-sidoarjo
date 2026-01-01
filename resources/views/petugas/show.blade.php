@extends('layouts.petugas')

@section('title', 'Detail Laporan #' . $report->id)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="bi bi-file-text"></i> Detail Laporan #{{ $report->id }}</h2>
                <a href="{{ route('petugas.reports') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Laporan</h5>
                    <span class="badge {{ $report->status_badge }} fs-6">{{ $report->status }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="bi bi-person"></i> Nama Pelapor:</strong><br>
                            {{ $report->nama_pelapor }}
                        </div>
                        <div class="col-md-6">
                            <strong><i class="bi bi-telephone"></i> Nomor HP:</strong><br>
                            {{ $report->nomor_hp }}
                        </div>
                    </div>

                    @if($report->email)
                    <div class="mb-3">
                        <strong><i class="bi bi-envelope"></i> Email:</strong><br>
                        {{ $report->email }}
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong><i class="bi bi-geo-alt"></i> Lokasi:</strong><br>
                        {{ $report->lokasi }}
                    </div>

                    @if($report->hasGpsCoordinates())
                    <div class="mb-3">
                        <strong><i class="bi bi-map"></i> Koordinat GPS:</strong><br>
                        <div class="d-flex gap-3">
                            <span class="badge bg-primary">Lat: {{ $report->latitude }}</span>
                            <span class="badge bg-primary">Long: {{ $report->longitude }}</span>
                            <a href="{{ $report->google_maps_url }}" target="_blank" class="btn btn-sm btn-success">
                                <i class="bi bi-geo-alt"></i> Lihat di Maps
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong><i class="bi bi-card-text"></i> Deskripsi Masalah:</strong><br>
                        <div class="border rounded p-3 bg-light mt-2">
                            {{ $report->deskripsi }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong><i class="bi bi-calendar"></i> Tanggal Laporan:</strong><br>
                        {{ $report->created_at->format('d F Y, H:i') }} WIB
                    </div>

                    @if($report->foto)
                    <div class="mb-3">
                        <strong><i class="bi bi-image"></i> Foto Pendukung:</strong><br>
                        <img src="{{ Storage::url($report->foto) }}" alt="Foto Laporan" 
                             class="img-fluid rounded mt-2 shadow-sm" style="max-height: 400px;">
                    </div>
                    @endif

                    @if($report->catatan_admin)
                    <div class="alert alert-info">
                        <strong><i class="bi bi-chat-left-text"></i> Catatan Admin:</strong><br>
                        {{ $report->catatan_admin }}
                    </div>
                    @endif

                    @if($report->catatan_petugas)
                    <div class="alert alert-warning">
                        <strong><i class="bi bi-chat-left-dots"></i> Catatan Petugas:</strong><br>
                        {{ $report->catatan_petugas }}
                    </div>
                    @endif

                    @if($report->status == 'Ditolak' && $report->alasan_penolakan)
                    <div class="alert alert-danger">
                        <h6><i class="bi bi-x-circle-fill"></i> Laporan Ditolak</h6>
                        <strong>Alasan Penolakan:</strong><br>
                        <div class="border-start border-danger border-3 ps-3 mt-2">
                            {{ $report->alasan_penolakan }}
                        </div>
                        <hr>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> Ditolak pada: {{ $report->updated_at->format('d F Y, H:i') }} WIB
                        </small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Form Input Kriteria SAW -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calculator"></i> Tentukan Kriteria Penilaian SAW</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> <strong>Informasi:</strong> Isi kriteria di bawah untuk menentukan prioritas perbaikan jalan berdasarkan metode SAW (Simple Additive Weighting).
                    </div>

                    <form action="{{ route('petugas.update-kriteria', $report->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- C1: Tingkat Kerusakan (40%) -->
                            <div class="col-md-6 mb-3">
                                <label for="tingkat_kerusakan" class="form-label fw-bold">
                                    C1: Tingkat Kerusakan 
                                    <span class="badge bg-primary">Bobot: 40%</span>
                                </label>
                                <select name="tingkat_kerusakan" id="tingkat_kerusakan" class="form-select" required>
                                    <option value="">-- Pilih Tingkat Kerusakan --</option>
                                    <option value="1" {{ ($report->tingkat_kerusakan ?? 0) == 1 ? 'selected' : '' }}>1 - Sangat Ringan (Retak halus)</option>
                                    <option value="2" {{ ($report->tingkat_kerusakan ?? 0) == 2 ? 'selected' : '' }}>2 - Ringan (Retak kecil)</option>
                                    <option value="3" {{ ($report->tingkat_kerusakan ?? 0) == 3 ? 'selected' : '' }}>3 - Sedang (Lubang kecil)</option>
                                    <option value="4" {{ ($report->tingkat_kerusakan ?? 0) == 4 ? 'selected' : '' }}>4 - Berat (Lubang besar)</option>
                                    <option value="5" {{ ($report->tingkat_kerusakan ?? 0) == 5 ? 'selected' : '' }}>5 - Sangat Berat (Jalan rusak parah)</option>
                                </select>
                            </div>

                            <!-- C2: Lokasi Strategis (30%) -->
                            <div class="col-md-6 mb-3">
                                <label for="lokasi_strategis" class="form-label fw-bold">
                                    C2: Lokasi Strategis 
                                    <span class="badge bg-success">Bobot: 30%</span>
                                </label>
                                <select name="lokasi_strategis" id="lokasi_strategis" class="form-select" required>
                                    <option value="">-- Pilih Lokasi Strategis --</option>
                                    <option value="1" {{ ($report->lokasi_strategis ?? 0) == 1 ? 'selected' : '' }}>1 - Jalan Desa</option>
                                    <option value="2" {{ ($report->lokasi_strategis ?? 0) == 2 ? 'selected' : '' }}>2 - Jalan Penghubung Desa</option>
                                    <option value="3" {{ ($report->lokasi_strategis ?? 0) == 3 ? 'selected' : '' }}>3 - Perempatan</option>
                                    <option value="4" {{ ($report->lokasi_strategis ?? 0) == 4 ? 'selected' : '' }}>4 - Jalan Utama</option>
                                    <option value="5" {{ ($report->lokasi_strategis ?? 0) == 5 ? 'selected' : '' }}>5 - Jalan Protokol</option>
                                </select>
                            </div>

                            <!-- C3: Jumlah Pengguna (20%) -->
                            <div class="col-md-6 mb-3">
                                <label for="jumlah_pengguna" class="form-label fw-bold">
                                    C3: Jumlah Pengguna Jalan 
                                    <span class="badge bg-warning text-dark">Bobot: 20%</span>
                                </label>
                                <select name="jumlah_pengguna" id="jumlah_pengguna" class="form-select" required>
                                    <option value="">-- Pilih Intensitas Pengguna --</option>
                                    <option value="1" {{ ($report->jumlah_pengguna ?? 0) == 1 ? 'selected' : '' }}>1 - Sangat Sepi (< 10 kendaraan/jam)</option>
                                    <option value="2" {{ ($report->jumlah_pengguna ?? 0) == 2 ? 'selected' : '' }}>2 - Sepi (10-50 kendaraan/jam)</option>
                                    <option value="3" {{ ($report->jumlah_pengguna ?? 0) == 3 ? 'selected' : '' }}>3 - Sedang (50-100 kendaraan/jam)</option>
                                    <option value="4" {{ ($report->jumlah_pengguna ?? 0) == 4 ? 'selected' : '' }}>4 - Ramai (100-200 kendaraan/jam)</option>
                                    <option value="5" {{ ($report->jumlah_pengguna ?? 0) == 5 ? 'selected' : '' }}>5 - Sangat Ramai (> 200 kendaraan/jam)</option>
                                </select>
                            </div>

                            <!-- C4: Kedekatan Fasum (10%) -->
                            <div class="col-md-6 mb-3">
                                <label for="kedekatan_fasum" class="form-label fw-bold">
                                    C4: Kedekatan Fasilitas Umum 
                                    <span class="badge bg-info">Bobot: 10%</span>
                                </label>
                                <select name="kedekatan_fasum" id="kedekatan_fasum" class="form-select" required>
                                    <option value="">-- Pilih Kedekatan Fasilitas Umum --</option>
                                    <option value="1" {{ ($report->kedekatan_fasum ?? 0) == 1 ? 'selected' : '' }}>1 - Sangat Jauh (> 2km)</option>
                                    <option value="2" {{ ($report->kedekatan_fasum ?? 0) == 2 ? 'selected' : '' }}>2 - Jauh (1-2km)</option>
                                    <option value="3" {{ ($report->kedekatan_fasum ?? 0) == 3 ? 'selected' : '' }}>3 - Sedang (500m-1km)</option>
                                    <option value="4" {{ ($report->kedekatan_fasum ?? 0) == 4 ? 'selected' : '' }}>4 - Dekat (100-500m)</option>
                                    <option value="5" {{ ($report->kedekatan_fasum ?? 0) == 5 ? 'selected' : '' }}>5 - Sangat Dekat (< 100m)</option>
                                </select>
                                <small class="text-muted">Fasilitas umum: Sekolah, Rumah Sakit, Kantor Pemerintah, Pasar, dll.</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-calculator"></i> Simpan Kriteria & Hitung Prioritas SAW
                            </button>
                        </div>
                    </form>

                    @if($report->tingkat_kerusakan && $report->skor_saw)
                    <hr class="my-4">
                    <div class="text-center">
                        <h5 class="mb-3">Hasil Perhitungan SAW</h5>
                        <h3>
                            Prioritas: 
                            @if($report->prioritas <= 3)
                                <span class="badge bg-danger fs-4">{{ $report->prioritas }} (TINGGI)</span>
                            @elseif($report->prioritas <= 10)
                                <span class="badge bg-warning fs-4">{{ $report->prioritas }} (SEDANG)</span>
                            @else
                                <span class="badge bg-secondary fs-4">{{ $report->prioritas }}</span>
                            @endif
                        </h3>
                        <p class="mb-0">Skor SAW: <strong class="text-success fs-4">{{ number_format($report->skor_saw, 4) }}</strong></p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Detail SAW (Jika sudah ada) -->
            @if(isset($saw_detail) && $saw_detail)
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-calculator"></i> Detail Perhitungan SAW</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Kategori</th>
                                    <th>Nilai</th>
                                    <th>Normalisasi</th>
                                    <th>Bobot</th>
                                    <th>Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>C1: Tingkat Kerusakan</strong></td>
                                    <td><span class="badge bg-primary">{{ $saw_detail['kriteria']['tingkat_kerusakan']['label'] }}</span></td>
                                    <td class="text-center">{{ $saw_detail['kriteria']['tingkat_kerusakan']['nilai'] }}</td>
                                    <td class="text-center">{{ number_format($saw_detail['kriteria']['tingkat_kerusakan']['normalisasi'], 2) }}</td>
                                    <td class="text-center">{{ $saw_detail['kriteria']['tingkat_kerusakan']['bobot'] * 100 }}%</td>
                                    <td class="text-center"><strong>{{ number_format($saw_detail['kriteria']['tingkat_kerusakan']['skor'], 4) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>C2: Lokasi Strategis</strong></td>
                                    <td><span class="badge bg-success">{{ $saw_detail['kriteria']['lokasi_strategis']['label'] }}</span></td>
                                    <td class="text-center">{{ $saw_detail['kriteria']['lokasi_strategis']['nilai'] }}</td>
                                    <td class="text-center">{{ number_format($saw_detail['kriteria']['lokasi_strategis']['normalisasi'], 2) }}</td>
                                    <td class="text-center">{{ $saw_detail['kriteria']['lokasi_strategis']['bobot'] * 100 }}%</td>
                                    <td class="text-center"><strong>{{ number_format($saw_detail['kriteria']['lokasi_strategis']['skor'], 4) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>C3: Jumlah Pengguna</strong></td>
                                    <td><span class="badge bg-warning text-dark">{{ $saw_detail['kriteria']['jumlah_pengguna']['label'] }}</span></td>
                                    <td class="text-center">{{ $saw_detail['kriteria']['jumlah_pengguna']['nilai'] }}</td>
                                    <td class="text-center">{{ number_format($saw_detail['kriteria']['jumlah_pengguna']['normalisasi'], 2) }}</td>
                                    <td class="text-center">{{ $saw_detail['kriteria']['jumlah_pengguna']['bobot'] * 100 }}%</td>
                                    <td class="text-center"><strong>{{ number_format($saw_detail['kriteria']['jumlah_pengguna']['skor'], 4) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>C4: Kedekatan Fasum</strong></td>
                                    <td><span class="badge bg-info">{{ $saw_detail['kriteria']['kedekatan_fasum']['label'] }}</span></td>
                                    <td class="text-center">{{ $saw_detail['kriteria']['kedekatan_fasum']['nilai'] }}</td>
                                    <td class="text-center">{{ number_format($saw_detail['kriteria']['kedekatan_fasum']['normalisasi'], 2) }}</td>
                                    <td class="text-center">{{ $saw_detail['kriteria']['kedekatan_fasum']['bobot'] * 100 }}%</td>
                                    <td class="text-center"><strong>{{ number_format($saw_detail['kriteria']['kedekatan_fasum']['skor'], 4) }}</strong></td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="5" class="text-end"><strong>TOTAL SKOR SAW:</strong></td>
                                    <td class="text-center"><strong class="text-success fs-5">{{ number_format($saw_detail['skor_total'], 4) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info mt-3">
                        <h6><i class="bi bi-info-circle"></i> Rumus Perhitungan:</h6>
                        <p class="mb-0 small">
                            <strong>Skor SAW = </strong>
                            ({{ $saw_detail['kriteria']['tingkat_kerusakan']['nilai'] }}/5 × 0.4) +
                            ({{ $saw_detail['kriteria']['lokasi_strategis']['nilai'] }}/5 × 0.3) +
                            ({{ $saw_detail['kriteria']['jumlah_pengguna']['nilai'] }}/5 × 0.2) +
                            ({{ $saw_detail['kriteria']['kedekatan_fasum']['nilai'] }}/5 × 0.1) =
                            <strong>{{ number_format($saw_detail['skor_total'], 4) }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Update Status Form -->
            <div class="card shadow mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas.update-status', $report->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="Menunggu Verifikasi" {{ $report->status == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="Diverifikasi" {{ $report->status == 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                                <option value="Dalam Perbaikan" {{ $report->status == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                <option value="Selesai" {{ $report->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Ditolak" {{ $report->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div class="mb-3" id="alasan_penolakan_field" style="display: none;">
                            <label class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control" rows="3" 
                                      placeholder="Jelaskan alasan penolakan laporan ini">{{ $report->alasan_penolakan }}</textarea>
                            <small class="text-muted">Alasan ini akan disampaikan kepada pelapor</small>
                        </div>

                        <div class="mb-3" id="tanggal_mulai_field">
                            <label class="form-label fw-bold">Tanggal Mulai Perbaikan</label>
                            <input type="date" name="tanggal_mulai_perbaikan" class="form-control" 
                                   value="{{ $report->tanggal_mulai_perbaikan?->format('Y-m-d') }}">
                        </div>

                        <div class="mb-3" id="tanggal_selesai_field">
                            <label class="form-label fw-bold">Tanggal Selesai Perbaikan</label>
                            <input type="date" name="tanggal_selesai_perbaikan" class="form-control" 
                                   value="{{ $report->tanggal_selesai_perbaikan?->format('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan Petugas</label>
                            <textarea name="catatan_petugas" class="form-control" rows="3" 
                                      placeholder="Tambahkan catatan...">{{ $report->catatan_petugas }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Timeline Progress</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $report->status == 'Menunggu Verifikasi' ? 'active' : ($report->status == 'Ditolak' ? 'rejected' : 'completed') }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>Menunggu Verifikasi</strong>
                                <br><small class="text-muted">{{ $report->created_at->format('d M Y') }}</small>
                            </div>
                        </div>
                        
                        @if($report->status != 'Ditolak')
                            <div class="timeline-item {{ $report->status == 'Diverifikasi' ? 'active' : (in_array($report->status, ['Dalam Perbaikan', 'Selesai']) ? 'completed' : '') }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <strong>Diverifikasi</strong>
                                </div>
                            </div>
                            <div class="timeline-item {{ $report->status == 'Dalam Perbaikan' ? 'active' : ($report->status == 'Selesai' ? 'completed' : '') }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <strong>Dalam Perbaikan</strong>
                                    @if($report->tanggal_mulai_perbaikan)
                                        <br><small class="text-muted">{{ $report->tanggal_mulai_perbaikan->format('d M Y') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="timeline-item {{ $report->status == 'Selesai' ? 'completed' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <strong>Selesai</strong>
                                    @if($report->tanggal_selesai_perbaikan)
                                        <br><small class="text-muted">{{ $report->tanggal_selesai_perbaikan->format('d M Y') }}</small>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="timeline-item rejected">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <strong class="text-danger">Ditolak</strong>
                                    <br><small class="text-muted">{{ $report->updated_at->format('d M Y') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
    border-left: 2px solid #e9ecef;
}
.timeline-item:last-child {
    border-left: 2px solid transparent;
}
.timeline-marker {
    position: absolute;
    left: -7px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #e9ecef;
}
.timeline-item.active .timeline-marker {
    background: #007bff;
}
.timeline-item.completed .timeline-marker {
    background: #28a745;
}
.timeline-item.completed {
    border-left-color: #28a745;
}
.timeline-item.active {
    border-left-color: #007bff;
}
.timeline-item.rejected {
    border-left-color: #dc3545;
}
.timeline-item.rejected .timeline-marker {
    background: #dc3545;
}
.timeline-content {
    padding-left: 20px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const alasanPenolakanField = document.getElementById('alasan_penolakan_field');
    const alasanPenolakanTextarea = document.getElementById('alasan_penolakan');
    const tanggalMulaiField = document.getElementById('tanggal_mulai_field');
    const tanggalSelesaiField = document.getElementById('tanggal_selesai_field');

    function toggleFields() {
        const status = statusSelect.value;
        
        // Toggle alasan penolakan field
        if (status === 'Ditolak') {
            alasanPenolakanField.style.display = 'block';
            alasanPenolakanTextarea.required = true;
            // Sembunyikan tanggal perbaikan
            tanggalMulaiField.style.display = 'none';
            tanggalSelesaiField.style.display = 'none';
        } else {
            alasanPenolakanField.style.display = 'none';
            alasanPenolakanTextarea.required = false;
            // Tampilkan tanggal perbaikan
            tanggalMulaiField.style.display = 'block';
            tanggalSelesaiField.style.display = 'block';
        }
    }

    statusSelect.addEventListener('change', toggleFields);
    toggleFields(); // Run on page load
});
</script>
@endsection