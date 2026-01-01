@extends('layouts.app')

@section('title', 'Detail Laporan #' . $report->id)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-file-text"></i> Detail Laporan #{{ $report->id }}</h2>
                    <div>
                        <a href="{{ route('admin.reports') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('admin.edit', $report->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        @if($report->status == 'Selesai')
                            <a href="{{ route('admin.reports.preview-pdf', $report->id) }}" class="btn btn-info"
                                target="_blank">
                                <i class="bi bi-eye"></i> Preview PDF
                            </a>
                            <a href="{{ route('admin.reports.download-pdf', $report->id) }}" class="btn btn-success">
                                <i class="bi bi-download"></i> Download PDF
                            </a>
                        @endif
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

                        @if($report->hasGpsCoordinates())
                            <div class="mb-3">
                                <strong>Koordinat GPS:</strong><br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Latitude:</small> {{ $report->latitude }}
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Longitude:</small> {{ $report->longitude }}
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ $report->google_maps_url }}" target="_blank" class="btn btn-sm btn-success">
                                        <i class="bi bi-geo-alt"></i> Lihat di Google Maps
                                    </a>
                                </div>
                            </div>

                            @if($report->alamat_lengkap)
                                <div class="mb-3">
                                    <strong>Alamat Lengkap (GPS):</strong><br>
                                    <small class="text-muted">{{ $report->alamat_lengkap }}</small>
                                </div>
                            @endif
                        @endif

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
                                <img src="{{ Storage::url($report->foto) }}" alt="Foto Laporan" class="img-fluid rounded mt-2"
                                    style="max-height: 400px;">
                            </div>
                        @endif

                        @if($report->hasGpsCoordinates())
                            <div class="mb-3">
                                <strong>Peta Lokasi:</strong><br>
                                <div id="map" style="height: 300px; border-radius: 8px;" class="mt-2"></div>
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
                                            <strong>Tanggal Selesai:</strong>
                                            {{ $report->tanggal_selesai_perbaikan->format('d M Y') }}
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

                        @if($report->catatan_admin)
                            <div class="alert alert-info">
                                <strong><i class="bi bi-chat-left-text"></i> Catatan Admin:</strong><br>
                                {{ $report->catatan_admin }}
                            </div>
                        @endif

                        <!-- TAMBAHKAN ALERT DITOLAK -->
                        @if($report->status == 'Ditolak' && $report->alasan_penolakan)
                            <div class="alert alert-danger">
                                <h6><i class="bi bi-x-circle-fill"></i> Laporan Ditolak</h6>
                                <strong>Alasan Penolakan:</strong><br>
                                <div class="border-start border-danger border-3 ps-3 mt-2">
                                    {{ $report->alasan_penolakan }}
                                </div>
                                <hr>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> Ditolak pada: {{ $report->updated_at->format('d F Y, H:i') }}
                                    WIB
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Detail Perhitungan SAW -->
                @if(isset($saw_detail) && $saw_detail)
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-calculator"></i> Detail Perhitungan SAW (Simple Additive Weighting)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <h3>
                                    Prioritas:
                                    @if($saw_detail['prioritas'] == 1)
                                        <span class="badge bg-danger fs-4">🥇 Prioritas {{ $saw_detail['prioritas'] }}
                                            (TERTINGGI)</span>
                                    @elseif($saw_detail['prioritas'] == 2)
                                        <span class="badge bg-warning fs-4">🥈 Prioritas {{ $saw_detail['prioritas'] }}</span>
                                    @elseif($saw_detail['prioritas'] == 3)
                                        <span class="badge bg-info fs-4">🥉 Prioritas {{ $saw_detail['prioritas'] }}</span>
                                    @else
                                        <span class="badge bg-secondary fs-4">Prioritas {{ $saw_detail['prioritas'] }}</span>
                                    @endif
                                </h3>
                                <h4>Skor SAW Total: <span
                                        class="text-success">{{ number_format($saw_detail['skor_total'], 4) }}</span></h4>
                            </div>

                            <hr>

                            <h6 class="mb-3">Rincian Perhitungan per Kriteria:</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Kriteria</th>
                                            <th>Kategori</th>
                                            <th class="text-center">Nilai</th>
                                            <th class="text-center">Normalisasi</th>
                                            <th class="text-center">Bobot</th>
                                            <th class="text-center">Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>C1: Tingkat Kerusakan</strong></td>
                                            <td><span
                                                    class="badge bg-primary">{{ $saw_detail['kriteria']['tingkat_kerusakan']['label'] }}</span>
                                            </td>
                                            <td class="text-center">{{ $saw_detail['kriteria']['tingkat_kerusakan']['nilai'] }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($saw_detail['kriteria']['tingkat_kerusakan']['normalisasi'], 2) }}
                                            </td>
                                            <td class="text-center">
                                                {{ $saw_detail['kriteria']['tingkat_kerusakan']['bobot'] * 100 }}%
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($saw_detail['kriteria']['tingkat_kerusakan']['skor'], 4) }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>C2: Lokasi Strategis</strong></td>
                                            <td><span
                                                    class="badge bg-success">{{ $saw_detail['kriteria']['lokasi_strategis']['label'] }}</span>
                                            </td>
                                            <td class="text-center">{{ $saw_detail['kriteria']['lokasi_strategis']['nilai'] }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($saw_detail['kriteria']['lokasi_strategis']['normalisasi'], 2) }}
                                            </td>
                                            <td class="text-center">
                                                {{ $saw_detail['kriteria']['lokasi_strategis']['bobot'] * 100 }}%
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($saw_detail['kriteria']['lokasi_strategis']['skor'], 4) }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>C3: Jumlah Pengguna</strong></td>
                                            <td><span
                                                    class="badge bg-warning text-dark">{{ $saw_detail['kriteria']['jumlah_pengguna']['label'] }}</span>
                                            </td>
                                            <td class="text-center">{{ $saw_detail['kriteria']['jumlah_pengguna']['nilai'] }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($saw_detail['kriteria']['jumlah_pengguna']['normalisasi'], 2) }}
                                            </td>
                                            <td class="text-center">
                                                {{ $saw_detail['kriteria']['jumlah_pengguna']['bobot'] * 100 }}%
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($saw_detail['kriteria']['jumlah_pengguna']['skor'], 4) }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>C4: Kedekatan Fasum</strong></td>
                                            <td><span
                                                    class="badge bg-info">{{ $saw_detail['kriteria']['kedekatan_fasum']['label'] }}</span>
                                            </td>
                                            <td class="text-center">{{ $saw_detail['kriteria']['kedekatan_fasum']['nilai'] }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($saw_detail['kriteria']['kedekatan_fasum']['normalisasi'], 2) }}
                                            </td>
                                            <td class="text-center">
                                                {{ $saw_detail['kriteria']['kedekatan_fasum']['bobot'] * 100 }}%
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($saw_detail['kriteria']['kedekatan_fasum']['skor'], 4) }}</strong>
                                            </td>
                                        </tr>
                                        <tr class="table-success">
                                            <td colspan="5" class="text-end"><strong>TOTAL SKOR SAW:</strong></td>
                                            <td class="text-center"><strong
                                                    class="fs-5 text-success">{{ number_format($saw_detail['skor_total'], 4) }}</strong>
                                            </td>
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
            <div class="col-md-4">
                @if($report->hasGpsCoordinates())
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Info GPS</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <p class="mb-2">
                                    <strong>{{ $report->latitude }}, {{ $report->longitude }}</strong>
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="{{ $report->google_maps_url }}" target="_blank" class="btn btn-success btn-sm">
                                        <i class="bi bi-map"></i> Google Maps
                                    </a>
                                    <button type="button" class="btn btn-info btn-sm" onclick="copyCoordinates()">
                                        <i class="bi bi-copy"></i> Copy Koordinat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="mb-0">Timeline Progress</h6>
                    </div>
                    <div class="card-body">
                        <div class="status-timeline">
                            <div
                                class="status-item {{ $report->status == 'Menunggu Verifikasi' ? 'active' : ($report->status == 'Ditolak' ? 'rejected' : 'completed') }}">
                                <strong>Menunggu Verifikasi</strong>
                                <br><small class="text-muted">{{ $report->created_at->format('d M Y') }}</small>
                            </div>

                            @if($report->status != 'Ditolak')
                                <div
                                    class="status-item {{ $report->status == 'Diverifikasi' ? 'active' : (in_array($report->status, ['Dalam Perbaikan', 'Selesai']) ? 'completed' : '') }}">
                                    <strong>Diverifikasi</strong>
                                    @if(in_array($report->status, ['Diverifikasi', 'Dalam Perbaikan', 'Selesai']))
                                        <br><small class="text-muted">Verified</small>
                                    @endif
                                </div>
                                <div
                                    class="status-item {{ $report->status == 'Dalam Perbaikan' ? 'active' : ($report->status == 'Selesai' ? 'completed' : '') }}">
                                    <strong>Dalam Perbaikan</strong>
                                    @if($report->tanggal_mulai_perbaikan)
                                        <br><small
                                            class="text-muted">{{ $report->tanggal_mulai_perbaikan->format('d M Y') }}</small>
                                    @endif
                                </div>
                                <div class="status-item {{ $report->status == 'Selesai' ? 'completed' : '' }}">
                                    <strong>Selesai</strong>
                                    @if($report->tanggal_selesai_perbaikan)
                                        <br><small
                                            class="text-muted">{{ $report->tanggal_selesai_perbaikan->format('d M Y') }}</small>
                                    @endif
                                </div>
                            @else
                                <div class="status-item rejected">
                                    <strong class="text-danger">Ditolak</strong>
                                    <br><small class="text-muted">{{ $report->updated_at->format('d M Y') }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <style>
        .status-timeline .status-item {
            position: relative;
            padding: 10px 0;
            border-left: 2px solid #e9ecef;
            padding-left: 20px;
            margin-left: 10px;
        }

        .status-timeline .status-item:before {
            content: '';
            position: absolute;
            left: -6px;
            top: 15px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #e9ecef;
        }

        .status-timeline .status-item.active:before {
            background-color: #007bff;
        }

        .status-timeline .status-item.completed:before {
            background-color: #28a745;
        }

        .status-timeline .status-item.rejected:before {
            background-color: #dc3545;
        }

        .status-timeline .status-item.completed {
            border-left-color: #28a745;
        }

        .status-timeline .status-item.active {
            border-left-color: #007bff;
        }

        .status-timeline .status-item.rejected {
            border-left-color: #dc3545;
        }
    </style>

    @if($report->hasGpsCoordinates())
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOVYRIgupAurZup5y1PRh8Ismb1A3lLao&libraries=places&callback=initMap"></script>

        <script>
            function initMap() {
                const location = {
                    lat: {{ $report->latitude }},
                    lng: {{ $report->longitude }} 
                            };

                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 16,
                    center: location,
                });

                const marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: "{{ $report->lokasi }}",
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                                    <div style="max-width: 200px;">
                                        <h6>{{ $report->lokasi }}</h6>
                                        <p><strong>Pelapor:</strong> {{ $report->nama_pelapor }}</p>
                                        <p><small>{{ $report->created_at->format('d M Y, H:i') }}</small></p>
                                    </div>
                                `,
                });

                marker.addListener("click", () => {
                    infoWindow.open(map, marker);
                });
            }

            function copyCoordinates() {
                const coords = "{{ $report->latitude }}, {{ $report->longitude }}";
                navigator.clipboard.writeText(coords).then(() => {
                    alert('Koordinat berhasil disalin: ' + coords);
                });
            }
        </script>
    @endif
@endsection