<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: linear-gradient(180deg, #00d4ff 0%, #0099cc 100%);
            padding: 25px 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .logo-section i {
            font-size: 2.5rem;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: bold;
            line-height: 1.2;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            color: white;
            text-align: right;
        }

        .user-info .name {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .user-info .role {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Main Content */
        .main-content {
            padding: 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #ffd89b 0%, #ffbe76 100%);
            border-radius: 25px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        .doctor-icon {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .doctor-icon i {
            font-size: 3.5rem;
            color: #00d4ff;
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .welcome-section p {
            font-size: 1.2rem;
            color: #34495e;
            font-weight: 500;
        }

        /* User Info Card */
        .user-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .user-avatar {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .user-details h2 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .user-details p {
            color: #7f8c8d;
            font-size: 1rem;
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .menu-card {
            background: white;
            border-radius: 20px;
            padding: 35px 25px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 3px solid transparent;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .menu-card.purple {
            border-color: #667eea;
        }

        .menu-card.purple:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .menu-card.pink {
            border-color: #f093fb;
        }

        .menu-card.pink:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .menu-card.blue {
            border-color: #4facfe;
        }

        .menu-card.blue:hover {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .menu-card.orange {
            border-color: #ffd89b;
        }

        .menu-card.orange:hover {
            background: linear-gradient(135deg, #ffd89b 0%, #ff9a56 100%);
        }

        .menu-icon-wrapper {
            width: 90px;
            height: 90px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .menu-card.purple .menu-icon-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .menu-card.pink .menu-icon-wrapper {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .menu-card.blue .menu-icon-wrapper {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .menu-card.orange .menu-icon-wrapper {
            background: linear-gradient(135deg, #ffd89b 0%, #ff9a56 100%);
        }

        .menu-icon-wrapper i {
            font-size: 3rem;
            color: white;
        }

        .menu-card:hover .menu-icon-wrapper {
            background: white;
        }

        .menu-card.purple:hover .menu-icon-wrapper i {
            color: #667eea;
        }

        .menu-card.pink:hover .menu-icon-wrapper i {
            color: #f093fb;
        }

        .menu-card.blue:hover .menu-icon-wrapper i {
            color: #4facfe;
        }

        .menu-card.orange:hover .menu-icon-wrapper i {
            color: #ff9a56;
        }

        .menu-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            transition: color 0.3s ease;
        }

        .menu-card:hover .menu-title {
            color: white;
        }

        /* Logout Button */
        .logout-section {
            text-align: center;
            margin-top: 40px;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            border: none;
            border-radius: 15px;
            padding: 18px 60px;
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(255,107,107,0.3);
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255,107,107,0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .user-info {
                text-align: center;
            }

            .main-content {
                padding: 20px;
            }
            
            .menu-grid {
                grid-template-columns: 1fr;
            }

            .welcome-section h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <i class="fas fa-pills"></i>
            <div class="logo-text">
                APOTEK<br>PELANGGAN
            </div>
        </div>
        <div class="header-right">
            <div class="user-info">
                <div class="name">{{ auth()->user()->name }}</div>
                <div class="role">Pelanggan</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="doctor-icon">
                <i class="fas fa-user-md"></i>
            </div>
            <h1>Selamat Datang!!</h1>
            <p>Ayo mulai memesan!</p>
        </div>

        <!-- User Info Card -->
        <div class="user-card">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-details">
                <h2>{{ auth()->user()->name }}</h2>
                <p><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse(auth()->user()->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="menu-grid">
            <a href="{{ route('pelanggan.cek-stok') }}" class="menu-card purple">
                <div class="menu-icon-wrapper">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="menu-title">Melihat Stok Obat</div>
            </a>

            <a href="{{ route('pelanggan.produk') }}" class="menu-card pink">
                <div class="menu-icon-wrapper">
                    <i class="fas fa-prescription-bottle-alt"></i>
                </div>
                <div class="menu-title">Melakukan Pemesanan Obat</div>
            </a>

            <a href="{{ route('pelanggan.pesanan') }}" class="menu-card blue">
                <div class="menu-icon-wrapper">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="menu-title">Melihat Status Pesanan</div>
            </a>

            <a href="{{ route('pelanggan.pembayaran') }}" class="menu-card orange">
                <div class="menu-icon-wrapper">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="menu-title">Melakukan Pembayaran</div>
            </a>
        </div>

        <!-- Logout Section -->
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> LOGOUT
                </button>
            </form>
        </div>
    </div>
</body>
</html>
