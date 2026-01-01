<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Perbaikan Jalan #{{ $report->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        
        .container {
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 18pt;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 10pt;
        }
        
        .report-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .report-id {
            font-size: 14pt;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background: #28a745;
            color: white;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            background: #007bff;
            color: white;
            padding: 8px 12px;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .info-table td:first-child {
            width: 35%;
            font-weight: bold;
            color: #495057;
        }
        
        .info-table td:last-child {
            width: 65%;
        }
        
        .saw-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .saw-table th,
        .saw-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        
        .saw-table th {
            background: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        .saw-table td {
            text-align: center;
        }
        
        .saw-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .saw-result {
            background: #d4edda;
            border: 2px solid #28a745;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            margin: 15px 0;
        }
        
        .saw-result h3 {
            color: #155724;
            margin-bottom: 5px;
        }
        
        .prioritas-badge {
            display: inline-block;
            padding: 5px 15px;
            background: #dc3545;
            color: white;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12pt;
        }
        
        .photo-section {
            text-align: center;
            margin: 15px 0;
        }
        
        .photo-section img {
            max-width: 400px;
            max-height: 300px;
            border: 2px solid #dee2e6;
            border-radius: 5px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
        
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 10px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
            font-weight: bold;
        }
        
        .alert {
            padding: 10px 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        
        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .mb-10 {
            margin-bottom: 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN HASIL PERBAIKAN JALAN</h1>
            <p>Sistem Pelaporan Digital Kabupaten Sidoarjo</p>
            <p>Dinas Pekerjaan Umum dan Penataan Ruang</p>
        </div>

        <!-- Report Info -->
        <div class="report-info">
            <div class="report-id">Laporan #{{ $report->id }}</div>
            <div>Status: <span class="status-badge">{{ $report->status }}</span></div>
            <div style="margin-top: 5px; font-size: 9pt; color: #666;">
                Tanggal Cetak: {{ date('d F Y, H:i') }} WIB
            </div>
        </div>

        <!-- Data Pelapor -->
        <div class="section">
            <div class="section-title">INFORMASI PELAPOR</div>
            <table class="info-table">
                <tr>
                    <td>Nama Pelapor</td>
                    <td>{{ $report->nama_pelapor }}</td>
                </tr>
                <tr>
                    <td>Nomor HP</td>
                    <td>{{ $report->nomor_hp }}</td>
                </tr>
                @if($report->email)
                <tr>
                    <td>Email</td>
                    <td>{{ $report->email }}</td>
                </tr>
                @endif
                <tr>
                    <td>Tanggal Laporan</td>
                    <td>{{ $report->created_at->format('d F Y, H:i') }} WIB</td>
                </tr>
            </table>
        </div>

        <!-- Lokasi & Deskripsi -->
        <div class="section">
            <div class="section-title">DETAIL LOKASI & KERUSAKAN</div>
            <table class="info-table">
                <tr>
                    <td>Lokasi</td>
                    <td>{{ $report->lokasi }}</td>
                </tr>
                @if($report->alamat_lengkap)
                <tr>
                    <td>Alamat Lengkap</td>
                    <td>{{ $report->alamat_lengkap }}</td>
                </tr>
                @endif
                @if($report->hasGpsCoordinates())
                <tr>
                    <td>Koordinat GPS</td>
                    <td>Lat: {{ $report->latitude }}, Long: {{ $report->longitude }}</td>
                </tr>
                @endif
                <tr>
                    <td>Deskripsi Kerusakan</td>
                    <td>{{ $report->deskripsi }}</td>
                </tr>
            </table>
        </div>

        <!-- Timeline Penanganan -->
        <div class="section">
            <div class="section-title">TIMELINE PENANGANAN</div>
            <table class="info-table">
                <tr>
                    <td>Tanggal Laporan Masuk</td>
                    <td>{{ $report->created_at->format('d F Y') }}</td>
                </tr>
                @if($report->tanggal_mulai_perbaikan)
                <tr>
                    <td>Tanggal Mulai Perbaikan</td>
                    <td>{{ $report->tanggal_mulai_perbaikan->format('d F Y') }}</td>
                </tr>
                @endif
                @if($report->tanggal_selesai_perbaikan)
                <tr>
                    <td>Tanggal Selesai Perbaikan</td>
                    <td>{{ $report->tanggal_selesai_perbaikan->format('d F Y') }}</td>
                </tr>
                @endif
                @if($report->durasi_penanganan)
                <tr>
                    <td>Total Durasi Penanganan</td>
                    <td><strong>{{ $report->durasi_penanganan }} Hari</strong></td>
                </tr>
                @endif
            </table>
        </div>

        @if($report->catatan_admin)
        <div class="alert alert-info">
            <strong>Catatan Admin:</strong><br>
            {{ $report->catatan_admin }}
        </div>
        @endif

        <!-- Foto Dokumentasi -->
        @if($report->foto)
        <div class="section">
            <div class="section-title">DOKUMENTASI FOTO</div>
            <div class="photo-section">
                <img src="{{ public_path('storage/' . $report->foto) }}" alt="Foto Laporan">
                <p style="margin-top: 10px; font-size: 9pt; color: #666;">
                    Foto dokumentasi kerusakan jalan
                </p>
            </div>
        </div>
        @endif

        <!-- Halaman Baru untuk SAW -->
        @if($saw_detail)
        <div class="page-break"></div>
        
        <div class="section">
            <div class="section-title">ANALISIS PRIORITAS PERBAIKAN (METODE SAW)</div>
            
            <div class="saw-result">
                <h3>Hasil Penilaian Prioritas</h3>
                <p style="font-size: 14pt; margin: 10px 0;">
                    Prioritas: <span class="prioritas-badge">#{{ $saw_detail['prioritas'] }}</span>
                </p>
                <p style="font-size: 12pt;">
                    Skor SAW Total: <strong>{{ number_format($saw_detail['skor_total'], 4) }}</strong>
                </p>
            </div>

            <div class="alert alert-info">
                <strong>Metode SAW (Simple Additive Weighting)</strong><br>
                Sistem penilaian prioritas perbaikan jalan berdasarkan 4 kriteria: Tingkat Kerusakan (40%), 
                Lokasi Strategis (30%), Jumlah Pengguna Jalan (20%), dan Kedekatan Fasilitas Umum (10%).
            </div>

            <table class="saw-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Kriteria</th>
                        <th style="width: 20%;">Kategori</th>
                        <th style="width: 10%;">Nilai</th>
                        <th style="width: 15%;">Normalisasi</th>
                        <th style="width: 10%;">Bobot</th>
                        <th style="width: 20%;">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left;"><strong>C1: Tingkat Kerusakan</strong></td>
                        <td>{{ $saw_detail['kriteria']['tingkat_kerusakan']['label'] }}</td>
                        <td>{{ $saw_detail['kriteria']['tingkat_kerusakan']['nilai'] }}</td>
                        <td>{{ number_format($saw_detail['kriteria']['tingkat_kerusakan']['normalisasi'], 2) }}</td>
                        <td>40%</td>
                        <td><strong>{{ number_format($saw_detail['kriteria']['tingkat_kerusakan']['skor'], 4) }}</strong></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><strong>C2: Lokasi Strategis</strong></td>
                        <td>{{ $saw_detail['kriteria']['lokasi_strategis']['label'] }}</td>
                        <td>{{ $saw_detail['kriteria']['lokasi_strategis']['nilai'] }}</td>
                        <td>{{ number_format($saw_detail['kriteria']['lokasi_strategis']['normalisasi'], 2) }}</td>
                        <td>30%</td>
                        <td><strong>{{ number_format($saw_detail['kriteria']['lokasi_strategis']['skor'], 4) }}</strong></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><strong>C3: Jumlah Pengguna</strong></td>
                        <td>{{ $saw_detail['kriteria']['jumlah_pengguna']['label'] }}</td>
                        <td>{{ $saw_detail['kriteria']['jumlah_pengguna']['nilai'] }}</td>
                        <td>{{ number_format($saw_detail['kriteria']['jumlah_pengguna']['normalisasi'], 2) }}</td>
                        <td>20%</td>
                        <td><strong>{{ number_format($saw_detail['kriteria']['jumlah_pengguna']['skor'], 4) }}</strong></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><strong>C4: Kedekatan Fasum</strong></td>
                        <td>{{ $saw_detail['kriteria']['kedekatan_fasum']['label'] }}</td>
                        <td>{{ $saw_detail['kriteria']['kedekatan_fasum']['nilai'] }}</td>
                        <td>{{ number_format($saw_detail['kriteria']['kedekatan_fasum']['normalisasi'], 2) }}</td>
                        <td>10%</td>
                        <td><strong>{{ number_format($saw_detail['kriteria']['kedekatan_fasum']['skor'], 4) }}</strong></td>
                    </tr>
                    <tr style="background: #d4edda; font-weight: bold;">
                        <td colspan="5" style="text-align: right;">TOTAL SKOR SAW:</td>
                        <td style="font-size: 12pt; color: #155724;">{{ number_format($saw_detail['skor_total'], 4) }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 15px; font-size: 9pt; color: #666;">
                <strong>Rumus Perhitungan:</strong> 
                ({{ $saw_detail['kriteria']['tingkat_kerusakan']['nilai'] }}/5 × 0.4) + 
                ({{ $saw_detail['kriteria']['lokasi_strategis']['nilai'] }}/5 × 0.3) + 
                ({{ $saw_detail['kriteria']['jumlah_pengguna']['nilai'] }}/5 × 0.2) + 
                ({{ $saw_detail['kriteria']['kedekatan_fasum']['nilai'] }}/5 × 0.1) = 
                {{ number_format($saw_detail['skor_total'], 4) }}
            </div>
        </div>
        @endif

        <!-- Tanda Tangan -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p><strong>Kepala Dinas PU</strong></p>
                <div class="signature-line">
                    ( _________________________ )
                </div>
            </div>
            <div class="signature-box">
                <p>Sidoarjo, {{ date('d F Y') }}</p>
                <p><strong>Petugas Pelaksana</strong></p>
                <div class="signature-line">
                    ( _________________________ )
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Sidoarjo</strong></p>
            <p>Jl. Raya Kamboja No. 1, Sidoarjo | Telp: (031) 1234567 | Email: pu@sidoarjokab.go.id</p>
            <p style="margin-top: 5px;">Dokumen ini dicetak secara otomatis dari Sistem Pelaporan Digital</p>
        </div>
    </div>
</body>
</html>