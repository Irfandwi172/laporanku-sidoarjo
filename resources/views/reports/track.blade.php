<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Laporan - LaporanKu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .status-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 8px;
            width: 16px;
            height: 16px;
            background: #fff;
            border: 3px solid #007bff;
            border-radius: 50%;
            z-index: 1;
        }
        .timeline-item.completed::before {
            background: #28a745;
            border-color: #28a745;
        }
        .timeline-item.current::before {
            background: #ffc107;
            border-color: #ffc107;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
        }
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
        }
        .report-id {
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="text-center mb-4">
            <h1 class="text-white">
                <i class="fas fa-search"></i>
                Tracking Laporan
            </h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Report Details -->
                <div class="card mb-4">
                    <div class="card-header bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">
                                <i class="fas fa-file-alt text-primary"></i>
                                Detail Laporan
                            </h3>
                            <span class="report-id">ID: #{{ $report->id }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <h4>{{ $report->title }}</h4>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $report->location }}
                                </p>
                                <p class="mb-3">{{ $report->description }}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="status-badge badge bg-{{ $report->status_badge_color }}">
                                    {{ $report->status_label }}
                                </span>
                                <p class="small text-muted mt-2">
                                    Dilaporkan: {{ $report->created_at->format('d/m/Y H:i') }}
                                </p>
                                @if($report->category)
                                    <span class="badge bg-secondary">{{ ucfirst($report->category) }}</span>
                                @endif
                                @if($report->priority)
                                    <span class="badge bg-{{ $report->priority == 'high' || $report->priority == 'urgent' ? 'danger' : ($report->priority == 'medium' ? 'warning' : 'info') }}">
                                        {{ ucfirst($report->priority) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($report->admin_notes)
                            <div class="alert alert-info">
                                <strong><i class="fas fa-sticky-note"></i> Catatan Admin:</strong><br>
                                {{ $report->admin_notes }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h3 class="mb-0">
                            <i class="fas fa-history text-primary"></i>
                            Timeline Status
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item {{ $report->status == 'pending' ? 'current' : 'completed' }}">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">📝 Laporan Diterima</h6>
                                        <p class="card-text small text-muted">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                                        <p class="card-text">Laporan Anda telah berhasil dikirim dan sedang menunggu verifikasi.</p>
                                    </div>
                                </div>
                            </div>

                            @if(in_array($report->status, ['verified', 'in_progress', 'completed']))
                                <div class="timeline-item {{ $report->status == 'verified' ? 'current' : 'completed' }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">✅ Laporan Diverifikasi</h6>
                                            <p class="card-text">Laporan telah diverifikasi dan akan segera ditindaklanjuti oleh tim terkait.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(in_array($report->status, ['in_progress', 'completed']))
                                <div class="timeline-item {{ $report->status == 'in_progress' ? 'current' : 'completed' }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">🔧 Dalam Perbaikan</h6>
                                            @if($report->start_repair_date)
                                                <p class="card-text small text-muted">{{ $report->start_repair_date->format('d/m/Y H:i') }}</p>
                                            @endif
                                            <p class="card-text">Tim sedang melakukan perbaikan terhadap masalah yang dilaporkan.</p>
                                            @if($report->estimated_duration_days)
                                                <p class="card-text">
                                                    <small class="text-info">
                                                        <i class="fas fa-clock"></i>
                                                        Estimasi penyelesaian: {{ $report->estimated_duration_days }} hari
                                                    </small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($report->status == 'completed')
                                <div class="timeline-item completed">
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <h6 class="card-title text-success">🎉 Selesai</h6>
                                            @if($report->completion_date)
                                                <p class="card-text small text-muted">{{ $report->completion_date->format('d/m/Y H:i') }}</p>
                                            @endif
                                            <p class="card-text">Perbaikan telah selesai dilakukan. Terima kasih atas laporan Anda!</p>
                                            @if($report->actual_duration)
                                                <p class="card-text">
                                                    <small class="text-success">
                                                        <i class="fas fa-check"></i>
                                                        Diselesaikan dalam {{ $report->actual_duration }} hari
                                                    </small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($report->status == 'rejected')
                                <div class="timeline-item">
                                    <div class="card border-danger">
                                        <div class="card-body">
                                            <h6 class="card-title text-danger">❌ Ditolak</h6>
                                            <p class="card-text">Laporan tidak dapat diproses lebih lanjut.</p>
                                            @if($report->admin_notes)
                                                <p class="card-text">
                                                    <small><strong>Alasan:</strong> {{ $report->admin_notes }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                @if($report->reporter_phone)
                    <div class="card mt-4">
                        <div class="card-body text-center">
                            <h6><i class="fab fa-whatsapp text-success"></i> Notifikasi WhatsApp</h6>
                            <p class="small text-muted">
                                Update status akan dikirim ke nomor {{ $report->reporter_phone }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Buat Laporan Baru
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-light ms-2">
                        <i class="fas fa-print"></i>
                        Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>