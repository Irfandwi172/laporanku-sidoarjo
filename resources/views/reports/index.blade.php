@extends('layouts.app')

@section('title', 'Beranda - Sistem Pelaporan')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 fw-bold text-primary mb-4">Laporanku - Sidoarjo</h1>
            <p class="lead mb-4">Sampaikan keluhan dan laporan Anda mengenai infrastruktur di Kabupaten Sidoarjo. Kami akan menindaklanjuti setiap laporan dengan transparansi penuh.</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <a href="{{ route('reports.create') }}" class="btn btn-primary btn-lg me-md-2">
                    <i class="bi bi-plus-circle"></i> Buat Laporan
                </a>
                <a href="{{ route('reports.check') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-search"></i> Cek Status Laporan
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="bi bi-clipboard-check text-primary" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Mudah Dilaporkan</h5>
                    <p class="card-text">Proses pelaporan yang sederhana dan cepat melalui form online.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="bi bi-eye text-success" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Transparan</h5>
                    <p class="card-text">Pantau progres penanganan laporan Anda secara real-time.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="bi bi-lightning text-warning" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Responsif</h5>
                    <p class="card-text">Tim kami akan merespons dan menindaklanjuti laporan dengan cepat.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How it Works -->
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4">Cara Kerja Sistem</h2>
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <strong>1</strong>
                    </div>
                    <h6>Buat Laporan</h6>
                    <p class="text-muted small">Isi form laporan dengan detail lengkap</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <strong>2</strong>
                    </div>
                    <h6>Verifikasi</h6>
                    <p class="text-muted small">Tim admin memverifikasi laporan Anda</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <strong>3</strong>
                    </div>
                    <h6>Dalam Perbaikan</h6>
                    <p class="text-muted small">Tim lapangan melakukan perbaikan</p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <strong>4</strong>
                    </div>
                    <h6>Selesai</h6>
                    <p class="text-muted small">Perbaikan selesai dan laporan ditutup</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection