<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Karyawan - Apotek')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')

    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #00bcd4 0%, #00838f 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }
        .content-wrapper {
            min-height: 100vh;
            background: #f8f9fa;
        }

        /* Pagination Fix */
        .pagination {
            font-size: 0.875rem;
            justify-content: center;
        }
        .pagination .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        /* Replace Prev / Next with simple arrows */
        .pagination .page-item:first-child .page-link *,
        .pagination .page-item:last-child .page-link * {
            display: none;
        }
        .pagination .page-item:first-child .page-link::after {
            content: "‹";
            font-size: 1.25rem;
            font-weight: bold;
            display: inline-block;
        }
        .pagination .page-item:last-child .page-link::after {
            content: "›";
            font-size: 1.25rem;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0 sidebar">
            <div class="p-3">
                <h4 class="text-white mb-4"><i class="fas fa-store"></i> Apotek</h4>
                <div class="text-white-50 small mb-3">
                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    <br>
                    <span class="badge bg-info">Karyawan</span>
                </div>
            </div>

            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('karyawan.transaksi.*') ? 'active' : '' }}" href="{{ route('karyawan.transaksi.index') }}">
                    <i class="fas fa-cash-register"></i> Transaksi
                </a>
                <a class="nav-link {{ request()->routeIs('karyawan.pesanan.*') ? 'active' : '' }}" href="{{ route('karyawan.pesanan.index') }}">
                    <i class="fas fa-shopping-cart"></i> Pesanan
                </a>
                <a class="nav-link {{ request()->routeIs('karyawan.obat.*') ? 'active' : '' }}" href="{{ route('karyawan.obat.index') }}">
                    <i class="fas fa-pills"></i> Obat
                </a>
                <hr class="text-white">
                <form id="logoutFormKaryawan" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="button" class="nav-link border-0 bg-transparent w-100 text-start" onclick="confirmLogoutKaryawan()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 content-wrapper">
            <div class="bg-white border-bottom py-3 px-4 mb-4">
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            </div>

            <div class="px-4">
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

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmLogoutKaryawan() {
        Swal.fire({
            icon: 'warning',
            title: 'Keluar?',
            text: 'Apakah Anda yakin ingin logout?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ff4444',
            cancelButtonColor: '#007bff',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutFormKaryawan').submit();
            }
        });
    }
</script>
@stack('scripts')
</body>
</html>
