@extends('layouts.app')

@section('title', 'Laporan Berhasil Dikirim')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow text-center">
                <div class="card-body py-5">
                    <div class="text-success mb-4">
                        <i class="bi bi-check-circle" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-success mb-3">Laporan Berhasil Dikirim!</h3>
                    <p class="text-muted mb-4">
                        Terima kasih atas laporan Anda. Tim kami akan segera memproses dan menindaklanjuti 
                        laporan yang telah Anda sampaikan.
                    </p>
                    <div class="alert alert-info text-start">
                        <h6><i class="bi bi-lightbulb"></i> Tips:</h6>
                        <ul class="mb-0 small">
                            <li>Simpan nomor HP Anda untuk cek status laporan</li>
                            <li>Anda akan dapat memantau progres penanganan secara real-time</li>
                            <li>Estimasi waktu penanganan akan ditampilkan setelah verifikasi</li>
                        </ul>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('reports.check') }}" class="btn btn-primary">
                            <i class="bi bi-search"></i> Cek Status Laporan
                        </a>
                        <a href="{{ route('reports.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> Buat Laporan Lagi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection