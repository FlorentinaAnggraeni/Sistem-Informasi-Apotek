<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Kill Bootstrap Pagination SVG -->
    <style>
        /* PRIORITY 1: Kill ALL SVG before anything else */
        nav[role="navigation"] svg,
        nav[aria-label="pagination"] svg,
        .pagination svg,
        svg[class*="chevron"],
        svg[class*="arrow"] {
            display: none !important;
        }
    </style>
    
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #00a8cc;
            --secondary-color: #ff8800;
        }
        
        body {
            overflow-x: hidden;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #00d4ff 0%, #00a8cc 100%);
            padding: 20px 0;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            color: white;
            border-bottom: 2px solid rgba(255,255,255,0.2);
            margin-bottom: 20px;
        }
        
        .sidebar-header h4 {
            margin: 0;
            font-weight: bold;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin: 5px 15px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }
        
        .sidebar-menu a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f5f7fa;
        }
        
        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .content-area {
            padding: 30px;
        }
        
        .card-dashboard {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .stat-card {
            text-align: center;
            padding: 20px;
        }
        
        .stat-card .icon {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        
        .stat-card .number {
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .stat-card .label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #ff9933 0%, #ff8800 100%);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 136, 0, 0.4);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Pagination Fix - Completely override Tailwind/Laravel default pagination */
        
        /* Main pagination container */
        .pagination {
            display: flex !important;
            gap: 5px;
            position: relative;
            z-index: 10;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        /* All pagination items */
        .pagination .page-item {
            display: inline-block;
        }
        
        /* All pagination links */
        .pagination .page-link {
            position: relative;
            display: block;
            padding: 0.375rem 0.75rem;
            font-size: 14px;
            color: #0d6efd;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out;
            overflow: hidden !important;
            z-index: 11;
        }
        
        .pagination .page-link:hover {
            z-index: 12;
            color: #0a58ca;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        .pagination .page-item.active .page-link {
            z-index: 13;
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }
        
        /* NUCLEAR OPTION: Kill all SVG and nested elements in prev/next */
        .pagination .page-item:first-child .page-link > *,
        .pagination .page-item:last-child .page-link > *,
        .pagination .page-item:first-child .page-link *,
        .pagination .page-item:last-child .page-link * {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            width: 0 !important;
            height: 0 !important;
            max-width: 0 !important;
            max-height: 0 !important;
            position: absolute !important;
            left: -9999px !important;
        }
        
        /* Reset all text content */
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            font-size: 0 !important;
            line-height: 1 !important;
            overflow: hidden !important;
            text-indent: 0 !important;
        }
        
        /* Add our own clean text */
        .pagination .page-item:first-child .page-link::before {
            content: "‹ Previous" !important;
            font-size: 14px !important;
            line-height: normal !important;
            display: inline-block !important;
            position: relative !important;
            z-index: 12;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        .pagination .page-item:last-child .page-link::before {
            content: "Next ›" !important;
            font-size: 14px !important;
            line-height: normal !important;
            display: inline-block !important;
            position: relative !important;
            z-index: 12;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* Kill ALL SVG elements anywhere in pagination */
        .pagination svg,
        .pagination .page-link svg,
        .pagination .page-item svg {
            display: none !important;
            visibility: hidden !important;
            width: 0 !important;
            height: 0 !important;
            max-width: 0 !important;
            max-height: 0 !important;
            position: absolute !important;
            left: -9999px !important;
            overflow: hidden !important;
        }
        
        /* Kill any path/polygon inside pagination */
        .pagination path,
        .pagination polygon,
        .pagination polyline {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-pills"></i> APOTEK</h4>
            <small>{{ strtoupper(auth()->user()->role) }}</small>
        </div>
        <ul class="sidebar-menu">
            @yield('sidebar-menu')
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div>
                <button class="btn d-md-none" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="fw-bold">
                    <span class="fw-bold">Selamat Datang, {{ auth()->user()->name }}</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">{{ auth()->user()->email }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('toggleSidebar')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            
            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>