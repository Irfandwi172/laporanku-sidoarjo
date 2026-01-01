@extends('layouts.app')

@section('title', 'Edit Status Laporan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title">Edit Status & Kriteria SAW Laporan #{{ $report->id }}</h3>
                </div>
                <form action="{{ route('admin.reports.update-status', $report->id) }}" method="POST" id="editReportForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Alert untuk error validasi -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Terdapat Kesalahan!</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Alert untuk error tanggal (JavaScript) -->
                        <div class="alert alert-danger alert-dismissible fade show d-none" role="alert" id="dateErrorAlert">
                            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Kesalahan Tanggal!</h5>
                            <p class="mb-0" id="dateErrorMessage"></p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                        <!-- Status & Basic Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label fw-bold">Status Laporan <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="Menunggu Verifikasi" {{ ($report->status ?? 'Menunggu Verifikasi') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                        <option value="Diverifikasi" {{ ($report->status ?? '') == 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                                        <option value="Dalam Perbaikan" {{ ($report->status ?? '') == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                        <option value="Selesai" {{ ($report->status ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="Ditolak" {{ ($report->status ?? '') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3" id="alasan_penolakan_field" style="display: none;">
                                    <label for="alasan_penolakan" class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                    <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control" rows="3" placeholder="Jelaskan alasan penolakan laporan ini">{{ old('alasan_penolakan', $report->alasan_penolakan) }}</textarea>
                                    <small class="text-muted">Alasan ini akan disampaikan kepada pelapor</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="estimasi_durasi" class="form-label fw-bold">Estimasi Durasi (hari)</label>
                                    <input type="number" class="form-control" id="estimasi_durasi" name="estimasi_durasi" 
                                           value="{{ old('estimasi_durasi', $report->estimasi_durasi) }}" min="1">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4" id="tanggal_fields">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_mulai_perbaikan" class="form-label fw-bold">
                                        Tanggal Mulai Perbaikan
                                        <span class="text-danger" id="tanggalMulaiRequired" style="display: none;">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="tanggal_mulai_perbaikan" name="tanggal_mulai_perbaikan" 
                                           value="{{ old('tanggal_mulai_perbaikan', $report->tanggal_mulai_perbaikan?->format('Y-m-d')) }}">
                                    <small class="text-muted" id="tanggalMulaiHint"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_selesai_perbaikan" class="form-label fw-bold">
                                        Tanggal Selesai Perbaikan
                                        <span class="text-danger" id="tanggalSelesaiRequired" style="display: none;">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="tanggal_selesai_perbaikan" name="tanggal_selesai_perbaikan" 
                                           value="{{ old('tanggal_selesai_perbaikan', $report->tanggal_selesai_perbaikan?->format('Y-m-d')) }}">
                                    <small class="text-muted" id="tanggalSelesaiHint"></small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        
                        <!-- Kriteria SAW -->
                        <div class="alert alert-info">
                            <h5 class="mb-3"><i class="bi bi-calculator"></i> Kriteria Penilaian SAW (Simple Additive Weighting)</h5>
                            <p class="mb-0 small">Isi kriteria di bawah untuk menentukan prioritas perbaikan jalan berdasarkan metode SAW</p>
                        </div>

                        <div class="row">
                            <!-- C1: Tingkat Kerusakan (Bobot 40%) -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tingkat_kerusakan" class="form-label fw-bold">
                                        C1: Tingkat Kerusakan 
                                        <span class="badge bg-primary">Bobot: 40%</span>
                                    </label>
                                    <select name="tingkat_kerusakan" id="tingkat_kerusakan" class="form-control">
                                        <option value="0" {{ ($report->tingkat_kerusakan ?? 0) == 0 ? 'selected' : '' }}>-- Pilih Tingkat Kerusakan --</option>
                                        <option value="1" {{ ($report->tingkat_kerusakan ?? 0) == 1 ? 'selected' : '' }}>1 - Sangat Ringan (Retak halus)</option>
                                        <option value="2" {{ ($report->tingkat_kerusakan ?? 0) == 2 ? 'selected' : '' }}>2 - Ringan (Retak kecil)</option>
                                        <option value="3" {{ ($report->tingkat_kerusakan ?? 0) == 3 ? 'selected' : '' }}>3 - Sedang (Lubang kecil)</option>
                                        <option value="4" {{ ($report->tingkat_kerusakan ?? 0) == 4 ? 'selected' : '' }}>4 - Berat (Lubang besar)</option>
                                        <option value="5" {{ ($report->tingkat_kerusakan ?? 0) == 5 ? 'selected' : '' }}>5 - Sangat Berat (Jalan rusak parah)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- C2: Lokasi Strategis (Bobot 30%) -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="lokasi_strategis" class="form-label fw-bold">
                                        C2: Lokasi Strategis 
                                        <span class="badge bg-success">Bobot: 30%</span>
                                    </label>
                                    <select name="lokasi_strategis" id="lokasi_strategis" class="form-control">
                                        <option value="0" {{ ($report->lokasi_strategis ?? 0) == 0 ? 'selected' : '' }}>-- Pilih Lokasi Strategis --</option>
                                        <option value="1" {{ ($report->lokasi_strategis ?? 0) == 1 ? 'selected' : '' }}>1 - Jalan Desa</option>
                                        <option value="2" {{ ($report->lokasi_strategis ?? 0) == 2 ? 'selected' : '' }}>2 - Jalan Penghubung Desa</option>
                                        <option value="3" {{ ($report->lokasi_strategis ?? 0) == 3 ? 'selected' : '' }}>3 - Perempatan Jalan Arteri</option>
                                        <option value="4" {{ ($report->lokasi_strategis ?? 0) == 4 ? 'selected' : '' }}>4 - Jalan Utama</option>
                                        <option value="5" {{ ($report->lokasi_strategis ?? 0) == 5 ? 'selected' : '' }}>5 - Jalan Protokol</option>
                                    </select>
                                </div>
                            </div>

                            <!-- C3: Jumlah Pengguna Jalan (Bobot 20%) -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="jumlah_pengguna" class="form-label fw-bold">
                                        C3: Jumlah Pengguna Jalan 
                                        <span class="badge bg-warning text-dark">Bobot: 20%</span>
                                    </label>
                                    <select name="jumlah_pengguna" id="jumlah_pengguna" class="form-control">
                                        <option value="0" {{ ($report->jumlah_pengguna ?? 0) == 0 ? 'selected' : '' }}>-- Pilih Intensitas Pengguna --</option>
                                        <option value="1" {{ ($report->jumlah_pengguna ?? 0) == 1 ? 'selected' : '' }}>1 - Sangat Sepi (< 10 kendaraan/jam)</option>
                                        <option value="2" {{ ($report->jumlah_pengguna ?? 0) == 2 ? 'selected' : '' }}>2 - Sepi (10-50 kendaraan/jam)</option>
                                        <option value="3" {{ ($report->jumlah_pengguna ?? 0) == 3 ? 'selected' : '' }}>3 - Sedang (50-100 kendaraan/jam)</option>
                                        <option value="4" {{ ($report->jumlah_pengguna ?? 0) == 4 ? 'selected' : '' }}>4 - Ramai (100-200 kendaraan/jam)</option>
                                        <option value="5" {{ ($report->jumlah_pengguna ?? 0) == 5 ? 'selected' : '' }}>5 - Sangat Ramai (> 200 kendaraan/jam)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- C4: Kedekatan Fasilitas Umum (Bobot 10%) -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kedekatan_fasum" class="form-label fw-bold">
                                        C4: Kedekatan dengan Fasilitas Umum 
                                        <span class="badge bg-info">Bobot: 10%</span>
                                    </label>
                                    <select name="kedekatan_fasum" id="kedekatan_fasum" class="form-control">
                                        <option value="0" {{ ($report->kedekatan_fasum ?? 0) == 0 ? 'selected' : '' }}>-- Pilih Kedekatan Fasilitas Umum --</option>
                                        <option value="1" {{ ($report->kedekatan_fasum ?? 0) == 1 ? 'selected' : '' }}>1 - Sangat Jauh (> 2km dari fasilitas umum)</option>
                                        <option value="2" {{ ($report->kedekatan_fasum ?? 0) == 2 ? 'selected' : '' }}>2 - Jauh (1-2km dari fasilitas umum)</option>
                                        <option value="3" {{ ($report->kedekatan_fasum ?? 0) == 3 ? 'selected' : '' }}>3 - Sedang (500m-1km dari fasilitas umum)</option>
                                        <option value="4" {{ ($report->kedekatan_fasum ?? 0) == 4 ? 'selected' : '' }}>4 - Dekat (100-500m dari fasilitas umum)</option>
                                        <option value="5" {{ ($report->kedekatan_fasum ?? 0) == 5 ? 'selected' : '' }}>5 - Sangat Dekat (< 100m dari fasilitas umum)</option>
                                    </select>
                                    <small class="form-text text-muted">Fasilitas umum: Sekolah, Rumah Sakit, Kantor Pemerintah, Pasar, dll.</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Catatan Admin -->
                        <div class="form-group mb-3">
                            <label for="catatan_admin" class="form-label fw-bold">Catatan Admin</label>
                            <textarea name="catatan_admin" id="catatan_admin" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan">{{ $report->catatan_admin ?? '' }}</textarea>
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-info-circle"></i>
                            <strong>Informasi:</strong> Setelah mengisi kriteria SAW, sistem akan otomatis menghitung skor dan menentukan prioritas perbaikan jalan berdasarkan metode Simple Additive Weighting (SAW).
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Status & Hitung Prioritas
                        </button>
                        <a href="{{ route('admin.show', $report->id) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const tanggalFields = document.getElementById('tanggal_fields');
    const alasanPenolakanField = document.getElementById('alasan_penolakan_field');
    const tanggalMulai = document.getElementById('tanggal_mulai_perbaikan');
    const tanggalSelesai = document.getElementById('tanggal_selesai_perbaikan');
    const form = document.getElementById('editReportForm');
    const dateErrorAlert = document.getElementById('dateErrorAlert');
    const dateErrorMessage = document.getElementById('dateErrorMessage');

    // Tanggal hari ini untuk validasi
    const today = new Date().toISOString().split('T')[0];

    function toggleFields() {
        const status = statusSelect.value;
        
        // Toggle tanggal fields
        if (status === 'Dalam Perbaikan' || status === 'Selesai') {
            tanggalFields.style.display = 'block';
            
            // Set required untuk status Dalam Perbaikan
            if (status === 'Dalam Perbaikan') {
                tanggalMulai.required = true;
                document.getElementById('tanggalMulaiRequired').style.display = 'inline';
                document.getElementById('tanggalMulaiHint').textContent = 'Wajib diisi untuk status Dalam Perbaikan';
                document.getElementById('tanggalMulaiHint').className = 'text-danger small';
            } else {
                tanggalMulai.required = false;
                document.getElementById('tanggalMulaiRequired').style.display = 'none';
                document.getElementById('tanggalMulaiHint').textContent = '';
            }
            
            // Set required untuk status Selesai
            if (status === 'Selesai') {
                tanggalMulai.required = true;
                tanggalSelesai.required = true;
                document.getElementById('tanggalMulaiRequired').style.display = 'inline';
                document.getElementById('tanggalSelesaiRequired').style.display = 'inline';
                document.getElementById('tanggalSelesaiHint').textContent = 'Wajib diisi untuk status Selesai';
                document.getElementById('tanggalSelesaiHint').className = 'text-danger small';
            } else {
                tanggalSelesai.required = false;
                document.getElementById('tanggalSelesaiRequired').style.display = 'none';
                document.getElementById('tanggalSelesaiHint').textContent = '';
            }
        } else {
            tanggalFields.style.display = 'none';
            tanggalMulai.required = false;
            tanggalSelesai.required = false;
        }

        // Toggle alasan penolakan field
        if (status === 'Ditolak') {
            alasanPenolakanField.style.display = 'block';
            document.getElementById('alasan_penolakan').required = true;
        } else {
            alasanPenolakanField.style.display = 'none';
            document.getElementById('alasan_penolakan').required = false;
        }
    }

    function showDateError(message) {
        dateErrorMessage.textContent = message;
        dateErrorAlert.classList.remove('d-none');
        
        // Scroll ke alert
        dateErrorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Auto hide setelah 5 detik
        setTimeout(() => {
            dateErrorAlert.classList.add('d-none');
        }, 5000);
    }

    function validateDates() {
        const status = statusSelect.value;
        const mulai = tanggalMulai.value;
        const selesai = tanggalSelesai.value;
        
        // Reset border colors
        tanggalMulai.classList.remove('is-invalid');
        tanggalSelesai.classList.remove('is-invalid');
        
        // Validasi untuk status Dalam Perbaikan
        if (status === 'Dalam Perbaikan') {
            if (!mulai) {
                tanggalMulai.classList.add('is-invalid');
                showDateError('Tanggal mulai perbaikan wajib diisi untuk status "Dalam Perbaikan"');
                return false;
            }
            
            // Tanggal mulai tidak boleh di masa depan
            if (mulai > today) {
                tanggalMulai.classList.add('is-invalid');
                showDateError('Tanggal mulai perbaikan tidak boleh di masa depan!');
                return false;
            }
        }
        
        // Validasi untuk status Selesai
        if (status === 'Selesai') {
            if (!mulai) {
                tanggalMulai.classList.add('is-invalid');
                showDateError('Tanggal mulai perbaikan wajib diisi untuk status "Selesai"');
                return false;
            }
            
            if (!selesai) {
                tanggalSelesai.classList.add('is-invalid');
                showDateError('Tanggal selesai perbaikan wajib diisi untuk status "Selesai"');
                return false;
            }
            
            // Tanggal selesai harus setelah tanggal mulai
            if (selesai < mulai) {
                tanggalSelesai.classList.add('is-invalid');
                showDateError('Tanggal selesai perbaikan tidak boleh lebih awal dari tanggal mulai perbaikan!');
                return false;
            }
            
            // Tanggal selesai tidak boleh di masa depan
            if (selesai > today) {
                tanggalSelesai.classList.add('is-invalid');
                showDateError('Tanggal selesai perbaikan tidak boleh di masa depan untuk status "Selesai"!');
                return false;
            }
        }
        
        // Validasi umum: jika ada tanggal selesai, harus ada tanggal mulai
        if (selesai && !mulai) {
            tanggalMulai.classList.add('is-invalid');
            showDateError('Tanggal mulai perbaikan harus diisi jika tanggal selesai sudah diisi!');
            return false;
        }
        
        // Validasi umum: tanggal selesai harus setelah tanggal mulai
        if (mulai && selesai && selesai < mulai) {
            tanggalSelesai.classList.add('is-invalid');
            showDateError('Tanggal selesai perbaikan tidak boleh lebih awal dari tanggal mulai perbaikan!');
            return false;
        }
        
        return true;
    }

    // Event listeners
    statusSelect.addEventListener('change', toggleFields);
    
    // Real-time validation saat input tanggal
    tanggalMulai.addEventListener('change', function() {
        if (tanggalFields.style.display !== 'none') {
            validateDates();
        }
    });
    
    tanggalSelesai.addEventListener('change', function() {
        if (tanggalFields.style.display !== 'none') {
            validateDates();
        }
    });
    
    // Validasi saat form submit
    form.addEventListener('submit', function(e) {
        if (!validateDates()) {
            e.preventDefault();
            return false;
        }
    });

    // Initialize
    toggleFields();
});
</script>

<style>
.is-invalid {
    border-color: #dc3545 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(.375em + .1875rem) center;
    background-size: calc(.75em + .375rem) calc(.75em + .375rem);
}
</style>
@endsection