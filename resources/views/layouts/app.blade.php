<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Laporanku Sidoarjo</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
        }

        .status-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .status-timeline::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 1rem;
            bottom: 1rem;
            width: 2px;
            background: #dee2e6;
        }

        .status-item {
            position: relative;
            padding-bottom: 1.5rem;
            color: #6c757d;
        }

        .status-item::before {
            content: '';
            position: absolute;
            left: -1.75rem;
            top: 0.25rem;
            width: 1rem;
            height: 1rem;
            border: 2px solid #dee2e6;
            border-radius: 50%;
            background: white;
        }

        .status-item.active::before {
            border-color: #007bff;
            background: #007bff;
        }

        .status-item.completed::before {
            border-color: #28a745;
            background: #28a745;
        }

        .status-item.active,
        .status-item.completed {
            color: #212529;
        }

        .card-stats {
            border-left: 4px solid #007bff;
        }

        .footer {
            background: #f8f9fa;
            margin-top: auto;
        }

        .user-menu .dropdown-toggle::after {
            display: none;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(45deg, #007bff, #6f42c1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .notification-badge {
            position: relative;
        }

        .notification-badge::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background: #dc3545;
            border-radius: 50%;
            border: 2px solid white;
        }

        .admin-navbar {
            background: linear-gradient(45deg, #007bff, #0056b3) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .public-navbar {
            background: linear-gradient(45deg, #28a745, #20c997) !important;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
    </style>

    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav
        class="navbar navbar-expand-lg navbar-dark {{ request()->routeIs('admin.*') ? 'admin-navbar' : 'public-navbar' }}">
        <div class="container-fluid">
            <a class="navbar-brand"
                href="{{ request()->routeIs('admin.*') ? route('admin.dashboard') : route('home') }}">
                <i class="bi bi-clipboard-check"></i>
                Laporanku - Sidoarjo
                @if(request()->routeIs('admin.*'))
                    <small class="badge bg-warning text-dark ms-2">ADMIN</small>
                @endif
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Public Menu -->
                @if(!request()->routeIs('admin.*'))
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="bi bi-house"></i> Beranda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.create') ? 'active' : '' }}"
                                href="{{ route('reports.create') }}">
                                <i class="bi bi-plus-circle"></i> Buat Laporan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.check') ? 'active' : '' }}"
                                href="{{ route('reports.check') }}">
                                <i class="bi bi-search"></i> Cek Status
                            </a>
                        </li>
                    </ul>
                    
                @endif

                <!-- Admin Menu -->
                @if(request()->routeIs('admin.*') && auth()->check())
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reports') && !request()->routeIs('admin.reports.show') && !request()->routeIs('admin.reports.edit') ? 'active' : '' }}"
                                href="{{ route('admin.reports') }}">
                                <i class="bi bi-list-ul"></i> Laporan Aktif
                                @php
                                    $pendingReports = App\Models\Report::whereNotIn('status', ['Selesai', 'Ditolak'])->count();
                                @endphp
                                @if($pendingReports > 0)
                                    <span class="badge bg-warning text-dark ms-1">{{ $pendingReports }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.prioritas') ? 'active' : '' }}"
                                href="{{ route('admin.prioritas') }}">
                                <i class="bi bi-sort-down"></i> Prioritas SAW
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.laporan-selesai') ? 'active' : '' }}"
                                href="{{ route('admin.laporan-selesai') }}">
                                <i class="bi bi-check-circle"></i> Selesai
                                @php
                                    $selesaiCount = App\Models\Report::where('status', 'Selesai')->count();
                                @endphp
                                @if($selesaiCount > 0)
                                    <span class="badge bg-success ms-1">{{ $selesaiCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.laporan-ditolak') ? 'active' : '' }}"
                                href="{{ route('admin.laporan-ditolak') }}">
                                <i class="bi bi-x-circle"></i> Ditolak
                                @php
                                    $ditolakCount = App\Models\Report::where('status', 'Ditolak')->count();
                                @endphp
                                @if($ditolakCount > 0)
                                    <span class="badge bg-danger ms-1">{{ $ditolakCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                    <!-- Admin User Menu -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown user-menu">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="d-none d-md-block">
                                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                    <small class="text-light opacity-75">Administrator</small>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <h6 class="dropdown-header">
                                        <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                                        <small class="d-block text-muted">{{ auth()->user()->email }}</small>
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.reports') }}">
                                        <i class="bi bi-list-ul"></i> Laporan Aktif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route(name: 'admin.prioritas') }}">
                                        <i class="bi bi-sort-down"></i> Prioritas SAW
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.laporan-selesai') }}">
                                        <i class="bi bi-check-circle"></i> Laporan Selesai
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.laporan-ditolak') }}">
                                        <i class="bi bi-x-circle"></i> Laporan Ditolak
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                                        <i class="bi bi-box-arrow-up-right"></i> Lihat Situs Publik
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"
                                            onclick="return confirm('Yakin ingin logout?')">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endif

                <!-- Guest Admin Menu (jika belum login tapi di halaman admin) -->
                @if(request()->routeIs('admin.*') && !auth()->check())
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="bi bi-house"></i> Kembali ke Beranda
                            </a>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </nav>

    <!-- Breadcrumb (hanya untuk admin area) -->
    @if(request()->routeIs('admin.*') && auth()->check())
        <nav aria-label="breadcrumb" class="bg-light py-2">
            <div class="container-fluid">
                <ol class="breadcrumb mb-0">
                    @if(request()->routeIs('admin.reports*'))
                        @if(request()->routeIs('admin.reports.show'))
                            <li class="breadcrumb-item active" aria-current="page">
                                Detail Laporan #{{ request()->route('report') }}
                            </li>
                        @elseif(request()->routeIs('admin.reports.edit'))
                            <li class="breadcrumb-item active" aria-current="page">
                                Edit Laporan #{{ request()->route('report') }}
                            </li>
                        @endif
                    @endif
                </ol>
            </div>
        </nav>
    @endif

    <!-- Flash Messages -->
    @if(session('success') || session('error') || session('warning'))
        <div class="container-fluid mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container-fluid">
            <div class="row py-4">
                <div class="col-md-6">
                    <h6><i class="bi bi-clipboard-check"></i> Laporanku - Sidoarjo</h6>
                    <p class="text-muted small mb-0">
                        Sistem Pelaporan Digital Kabupaten Sidoarjo untuk melayani masyarakat dengan lebih baik.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">
                        © {{ date('Y') }} Pemerintah Kabupaten Sidoarjo.
                        @if(request()->routeIs('admin.*'))
                            <span class="badge bg-primary">Admin Panel v1.0</span>
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                const alerts = document.querySelectorAll('.alert:not(.alert-danger)');
                alerts.forEach(function (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    setTimeout(() => {
                        try {
                            bsAlert.close();
                        } catch (e) {
                            // Alert already closed
                        }
                    }, 5000);
                });
            }, 100);

            // Confirm logout
            const logoutForms = document.querySelectorAll('form[action*="logout"]');
            logoutForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    if (!confirm('Yakin ingin logout?')) {
                        e.preventDefault();
                    }
                });
            });

            // Auto-refresh notification count every 30 seconds (only in admin area)
            @if(request()->routeIs('admin.*') && auth()->check())
                setInterval(function () {
                    fetch('{{ route("admin.dashboard") }}')
                        .then(response => response.text())
                        .then(html => {
                            // Update notification badges if needed
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newBadge = doc.querySelector('.navbar-nav .badge');
                            const currentBadge = document.querySelector('.navbar-nav .badge');

                            if (newBadge && currentBadge) {
                                if (newBadge.textContent !== currentBadge.textContent) {
                                    currentBadge.textContent = newBadge.textContent;
                                    currentBadge.classList.add('pulse-animation');
                                    setTimeout(() => {
                                        currentBadge.classList.remove('pulse-animation');
                                    }, 1000);
                                }
                            }
                        })
                        .catch(error => console.log('Auto-refresh failed:', error));
                }, 30000);
            @endif
        });

        // Add pulse animation for notifications
        const style = document.createElement('style');
        style.textContent = `
            .pulse-animation {
                animation: pulse 0.5s ease-in-out;
            }
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
    </script>

    @stack('scripts')
</body>

</html>