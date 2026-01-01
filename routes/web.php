
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Petugas\AuthController as PetugasAuthController;

// Public Routes
Route::get('/', [ReportController::class, 'index'])->name('home');
Route::get('/lapor', [ReportController::class, 'create'])->name('reports.create');
Route::post('/lapor', [ReportController::class, 'store'])->name('reports.store');
Route::get('/sukses', [ReportController::class, 'success'])->name('reports.success');
Route::get('/cek-status', [ReportController::class, 'check'])->name('reports.check');
Route::post('/cek-status', [ReportController::class, 'status'])->name('reports.status');

// API for GPS features
Route::get('/api/reports/gps', [ReportController::class, 'getReportsWithGps'])->name('api.reports.gps');

// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/login or dashboard
    Route::get('/', function () {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    // Guest routes (not authenticated)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Authenticated admin routes
    Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

        // Dashboard & Reports
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/{report}', [AdminController::class, 'show'])->name('show');
        Route::get('/reports/{report}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('/reports/{report}/status', [AdminController::class, 'updateStatus'])->name('reports.update-status');

        Route::get('/laporan-selesai', [AdminController::class, 'laporanSelesai'])->name('laporan-selesai');
        Route::get('/laporan-ditolak', [AdminController::class, 'laporanDitolak'])->name('laporan-ditolak');

        // SAW Priority Routes
        Route::get('/prioritas', [AdminController::class, 'prioritas'])->name('prioritas');
        Route::post('/hitung-ulang-prioritas', [AdminController::class, 'hitungUlangPrioritas'])->name('hitung-ulang-prioritas');
        Route::get('/export-prioritas', [AdminController::class, 'exportPrioritas'])->name('export-prioritas');

        // SAW API & Statistics
        Route::get('/api/saw-statistics', [AdminController::class, 'sawStatistics'])->name('saw-statistics');

        Route::get('/reports/{report}/download-pdf', [AdminController::class, 'downloadPDF'])->name('reports.download-pdf');
        Route::get('/reports/{report}/preview-pdf', [AdminController::class, 'previewPDF'])->name('reports.preview-pdf');
    });
});

// ============================================
// PETUGAS ROUTES (TERPISAH DARI ADMIN)
// ============================================
Route::prefix('petugas')->name('petugas.')->group(function () {
    // Redirect /petugas to /petugas/login or dashboard
    Route::get('/', function () {
        if (auth()->check() && auth()->user()->role === 'petugas') {
            return redirect()->route('petugas.dashboard');
        }
        return redirect()->route('petugas.login');
    });

    // Guest routes (not authenticated)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [PetugasAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [PetugasAuthController::class, 'login'])->name('login.post');
    });

    // Authenticated petugas routes
    Route::middleware(['auth', \App\Http\Middleware\PetugasMiddleware::class])->group(function () {
        Route::post('/logout', [PetugasAuthController::class, 'logout'])->name('logout');

        // Dashboard & Reports
        Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports', [PetugasController::class, 'reports'])->name('reports');
        Route::get('/reports/{report}', [PetugasController::class, 'show'])->name('show');
        Route::put('/reports/{report}/status', [PetugasController::class, 'updateStatus'])->name('update-status');
        
        // ROUTE BARU: Update Kriteria SAW
        Route::put('/reports/{report}/kriteria', [PetugasController::class, 'updateKriteria'])->name('update-kriteria');

        // SAW Priority Routes
        Route::get('/prioritas', [PetugasController::class, 'prioritas'])->name('prioritas');
        Route::post('/hitung-prioritas', [PetugasController::class, 'hitungPrioritasSAW'])->name('hitung-prioritas');
    });
});