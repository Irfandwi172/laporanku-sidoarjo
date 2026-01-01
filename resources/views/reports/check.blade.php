@extends('layouts.app')

@section('title', 'Cek Status Laporan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="bi bi-search"></i> Cek Status Laporan</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <p class="text-muted mb-4">
                        Masukkan nomor HP yang Anda gunakan saat membuat laporan untuk melihat status terkini.
                    </p>

                    <form action="{{ route('reports.status') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nomor_hp" class="form-label">
                                Nomor HP <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="text" 
                                       class="form-control @error('nomor_hp') is-invalid @enderror" 
                                       id="nomor_hp" 
                                       name="nomor_hp" 
                                       value="{{ old('nomor_hp') }}" 
                                       placeholder="Contoh: 081234567890" 
                                       required>
                                @error('nomor_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle"></i> Gunakan nomor HP yang sama dengan saat membuat laporan
                            </small>
                        </div>

                        <div class="alert alert-light border">
                            <h6><i class="bi bi-shield-check"></i> Informasi:</h6>
                            <ul class="mb-0 small">
                                <li>Anda akan melihat <strong>semua laporan</strong> yang terdaftar dengan nomor HP ini</li>
                                <li>Status yang ditampilkan adalah status <strong>real-time</strong></li>
                                <li>Jika laporan ditolak, Anda akan melihat alasan penolakannya</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-info btn-lg">
                                <i class="bi bi-search"></i> Cek Status Laporan
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">
                        <i class="bi bi-lock"></i> Data Anda aman dan hanya digunakan untuk verifikasi
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection