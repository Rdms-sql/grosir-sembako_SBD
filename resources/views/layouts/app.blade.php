<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Grosir Sembako')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f6fa; }

        /* Sidebar */
        #sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1e293b;
            color: #fff;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        #sidebar.collapsed {
            transform: translateX(-250px);
        }
        #sidebar .brand {
            padding: 20px 16px;
            font-size: 1.1rem;
            font-weight: 700;
            border-bottom: 1px solid #334155;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #sidebar .nav-link {
            color: #cbd5e1;
            padding: 10px 16px;
            border-radius: 8px;
            margin: 2px 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background: #3b82f6;
            color: #fff;
        }
        #sidebar .nav-section {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            padding: 12px 16px 4px;
        }

        /* Overlay gelap saat sidebar terbuka di mobile */
        #sidebarOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        #sidebarOverlay.show {
            display: block;
        }

        /* Main content */
        #main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        #main-content.expanded {
            margin-left: 0;
        }

        /* Topbar */
        #topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .page-content { padding: 24px; }

        /* Hamburger button */
        #hamburgerBtn {
            background: none;
            border: none;
            font-size: 1.4rem;
            color: #1e293b;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            line-height: 1;
        }
        #hamburgerBtn:hover { background: #f1f5f9; }

        /* Close button di dalam sidebar */
        #closeSidebarBtn {
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 4px;
        }
        #closeSidebarBtn:hover { color: #fff; background: #334155; }

        /* Responsive: mobile default sidebar tersembunyi */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-250px);
            }
            #sidebar.open {
                transform: translateX(0);
            }
            #main-content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>

{{-- Overlay --}}
<div id="sidebarOverlay" onclick="tutupSidebar()"></div>

{{-- ===== SIDEBAR ===== --}}
<div id="sidebar">
    <div class="brand">
        <span>🛒 Grosir Sembako</span>
        <button id="closeSidebarBtn" onclick="tutupSidebar()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="mt-2">
        <div class="nav-section">Master</div>
        <a href="/supplier"
           class="nav-link {{ request()->is('supplier*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Supplier
        </a>
        <a href="/konsumen"
           class="nav-link {{ request()->is('konsumen*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Konsumen
        </a>
        <a href="/barang"
           class="nav-link {{ request()->is('barang*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Barang
        </a>

        <div class="nav-section">Pemesanan</div>
        <a href="/pemesanan-supplier"
           class="nav-link {{ request()->is('pemesanan-supplier*') ? 'active' : '' }}">
            <i class="bi bi-cart-plus"></i> Pesan ke Supplier
        </a>
        <a href="/pemesanan-konsumen"
           class="nav-link {{ request()->is('pemesanan-konsumen*') ? 'active' : '' }}">
            <i class="bi bi-bag-plus"></i> Pesan dari Konsumen
        </a>

        <div class="nav-section">Transaksi</div>
        <a href="/pembelian"
           class="nav-link {{ request()->is('pembelian*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Pembelian
        </a>
        <a href="/penjualan"
           class="nav-link {{ request()->is('penjualan*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> Penjualan
        </a>

        <div class="nav-section">Keuangan</div>
        <a href="/hutangs"
           class="nav-link {{ request()->is('hutangs*') ? 'active' : '' }}">
        <a href="/hutangs"
           class="nav-link {{ request()->is('hutangs*') ? 'active' : '' }}">
            <i class="bi bi-arrow-down-circle"></i> Hutang
        </a>
        <a href="/piutang"
           class="nav-link {{ request()->is('piutang*') ? 'active' : '' }}">
            <i class="bi bi-arrow-up-circle"></i> Piutang
        </a>

        <div class="nav-section">Retur</div>
        <a href="/retur-pembelian"
           class="nav-link {{ request()->is('retur-pembelian*') ? 'active' : '' }}">
            <i class="bi bi-arrow-return-left"></i> Retur Pembelian
        </a>
        <a href="/retur-penjualan"
           class="nav-link {{ request()->is('retur-penjualan*') ? 'active' : '' }}">
            <i class="bi bi-arrow-return-right"></i> Retur Penjualan
        </a>
    </nav>
</div>

{{-- ===== MAIN CONTENT ===== --}}
<div id="main-content">

    {{-- Topbar --}}
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            {{-- Tombol Hamburger --}}
            <button id="hamburgerBtn" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <span class="fw-semibold text-secondary">
                @yield('title', 'Dashboard')
            </span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">
                <i class="bi bi-person-circle"></i>
                {{ Auth::user()->nama_lengkap ?? 'User' }}
                <span class="badge bg-secondary ms-1">
                    {{ Auth::user()->role ?? '' }}
                </span>
            </span>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="page-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar      = document.getElementById('sidebar');
    const mainContent  = document.getElementById('main-content');
    const overlay      = document.getElementById('sidebarOverlay');
    const isMobile     = () => window.innerWidth <= 768;

    function toggleSidebar() {
        if (isMobile()) {
            // Mobile: slide in/out dengan overlay
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        } else {
            // Desktop: collapse/expand geser konten
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
    }

    function tutupSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        // Desktop juga bisa tutup
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
    }
</script>

@stack('scripts')
</body>
</html>