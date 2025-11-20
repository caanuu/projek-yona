<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PT. Surya Sukses Elektronik')</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active-bg: rgba(255, 255, 255, 0.1);
            --sidebar-active-text: #fff;
            --bg-body: #f1f5f9;
            --text-main: #334155;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background-color: var(--sidebar-bg);
            color: #fff;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
        }

        /* Header Sidebar Dipercantik */
        .sidebar-header {
            padding: 1.5rem;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), transparent);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .sidebar-content {
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1rem 0;
        }

        .sidebar-content::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .nav-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            margin: 1.5rem 1.5rem 0.5rem;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.8rem 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            font-size: 0.95rem;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar .nav-link.active {
            color: var(--sidebar-active-text);
            background-color: var(--sidebar-active-bg);
            border-left-color: var(--primary-color);
        }

        .sidebar .nav-link i {
            margin-right: 1rem;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .user-profile {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            background-color: rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
        }

        .main-wrapper {
            margin-left: 280px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .content {
            padding: 2rem;
            flex: 1;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1035;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s;
                backdrop-filter: blur(2px);
            }

            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
        }
    </style>
</head>

<body>

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center shadow-lg"
                    style="width: 48px; height: 48px; min-width: 48px;">
                    <i class="bi bi-lightning-charge-fill fs-4"></i>
                </div>
                <div style="line-height: 1.2;">
                    <h6 class="mb-0 text-white fw-bold text-uppercase" style="letter-spacing: 0.5px;">SURYA SUKSES</h6>
                    <span class="badge bg-warning text-dark mt-1"
                        style="font-size: 0.65rem; font-weight: 700;">INVENTARIS ELEKTRONIK</span>
                </div>
            </div>
        </div>

        <div class="sidebar-content">
            <div class="nav flex-column">

                {{-- 1. DASHBOARD (HANYA ADMIN) --}}
                @if (Auth::user()->isAdmin())
                    <div class="nav-label">Utama</div>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                @endif

                <div class="nav-label">Master Data</div>

                {{-- 2. SUPPLIER (ADMIN & GUDANG) --}}
                @if (Auth::user()->isAdmin() || Auth::user()->isGudang())
                    <a href="{{ route('supplier.index') }}"
                        class="nav-link {{ Request::is('supplier*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i> Data Supplier
                    </a>
                @endif

                {{-- 3. STOK BARANG (SEMUA ROLE) --}}
                <a href="{{ route('barang.index') }}"
                    class="nav-link {{ Request::is('barang*') && !Request::is('barang/list') && !Request::is('barang/rusak') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i> Daftar Barang
                </a>

                <div class="nav-label">Transaksi</div>

                {{-- 4. BARANG MASUK (ADMIN & GUDANG) --}}
                @if (Auth::user()->isAdmin() || Auth::user()->isGudang())
                    <a href="{{ route('transaksi-masuk.index') }}"
                        class="nav-link {{ Request::is('transaksi-masuk*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-down-square-fill"></i> Barang Masuk
                    </a>
                @endif

                {{-- 5. MUTASI KONDISI (ADMIN & GUDANG) --}}
                @if (Auth::user()->isAdmin() || Auth::user()->isGudang())
                    <a href="{{ route('mutasi-kondisi.create') }}"
                        class="nav-link {{ Request::is('mutasi-kondisi*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right"></i> Mutasi / Pindah Kondisi
                    </a>
                @endif

                {{-- 6. BARANG KELUAR (ADMIN & KASIR) --}}
                @if (Auth::user()->isAdmin() || Auth::user()->isKasir())
                    <a href="{{ route('transaksi-keluar.index') }}"
                        class="nav-link {{ Request::is('transaksi-keluar*') ? 'active' : '' }}">
                        <i class="bi bi-cart-check-fill"></i> Barang Keluar
                    </a>
                @endif

                {{-- 7. LAPORAN & STOK (ADMIN & GUDANG) --}}
                @if (Auth::user()->isAdmin() || Auth::user()->isGudang())
                    <div class="nav-label">Laporan Gudang</div>
                    <a href="{{ route('barang.list') }}" class="nav-link {{ Request::is('list') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-data"></i> Laporan Stok
                    </a>
                    <a href="{{ route('barang.rusak') }}" class="nav-link {{ Request::is('rusak') ? 'active' : '' }}">
                        <i class="bi bi-exclamation-octagon-fill"></i> Barang Rusak
                    </a>
                @endif

                {{-- 8. LAPORAN KEUANGAN (HANYA ADMIN) --}}
                @if (Auth::user()->isAdmin())
                    <div class="nav-label">Keuangan</div>
                    <a href="{{ route('laporan.index') }}"
                        class="nav-link {{ Request::is('laporan*') ? 'active' : '' }}">
                        <i class="bi bi-pie-chart-fill"></i> Laporan Keuangan
                    </a>
                @endif
            </div>
        </div>

        <div class="user-profile">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-gradient d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                    style="width: 40px; height: 40px; background: linear-gradient(45deg, #2563eb, #3b82f6);">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div class="text-white fw-semibold text-truncate" style="font-size: 0.9rem;">
                        {{ Auth::user()->name }}</div>
                    <div class="text-muted small text-truncate text-uppercase" style="font-size: 0.7rem;">
                        {{ Auth::user()->role }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-icon btn-sm text-danger" title="Logout">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="main-wrapper">
        <div class="topbar">
            <div class="d-flex align-items-center">
                <button id="sidebarToggle" class="btn btn-light shadow-sm d-lg-none me-3">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">@yield('title')</h5>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white px-3 py-2 rounded-pill shadow-sm border d-none d-md-flex align-items-center gap-2">
                    <i class="bi bi-calendar-event text-primary"></i>
                    <span class="text-muted small fw-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
        </div>

        <div class="content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4"
                    role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                        <div><strong class="d-block">Berhasil!</strong> {{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4"
                    role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
                        <div><strong class="d-block">Perhatian!</strong> {{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
        toggleBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>
    @stack('scripts')
</body>

</html>
