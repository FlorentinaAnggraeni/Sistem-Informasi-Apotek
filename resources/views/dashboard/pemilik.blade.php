<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilik</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Header */
        .header {
            background: linear-gradient(180deg, #00bcd4 0%, #0097a7 100%);
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

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #ffd89b 0%, #ffbe76 100%);
            border-radius: 20px;
            padding: 50px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .welcome-icon {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .welcome-icon i {
            font-size: 3.5rem;
            color: #00bcd4;
        }

        .welcome-banner h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .welcome-banner p {
            font-size: 1.15rem;
            color: #34495e;
            font-weight: 500;
        }

        /* User Info Card */
        .user-info-card {
            background: white;
            border-radius: 20px;
            padding: 25px 35px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-avatar {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }

        .user-details h3 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .user-details p {
            color: #7f8c8d;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 3px solid;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .stat-card.purple { border-color: #667eea; }
        .stat-card.pink { border-color: #f093fb; }
        .stat-card.blue { border-color: #4facfe; }
        .stat-card.orange { border-color: #ffd89b; }

        .stat-icon {
            width: 65px;
            height: 65px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 2rem;
            color: white;
        }

        .stat-card.purple .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card.pink .stat-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-card.blue .stat-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-card.orange .stat-icon {
            background: linear-gradient(135deg, #ffd89b 0%, #ff9a56 100%);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.95rem;
            font-weight: 500;
        }

        /* Menu Cards Grid */
        .menu-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .menu-card {
            background: white;
            border-radius: 15px;
            padding: 35px 25px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 3px solid;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .menu-card.purple { border-color: #667eea; }
        .menu-card.pink { border-color: #f093fb; }

        .menu-card-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
        }

        .menu-card.purple .menu-card-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .menu-card.pink .menu-card-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .menu-card-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .menu-card-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: #2c3e50;
        }

        /* Charts Section */
        .charts-section {
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Activity Table */
        .activity-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            font-size: 0.9rem;
        }

        th {
            font-weight: 600;
            color: #2c3e50;
        }

        td {
            color: #7f8c8d;
        }

        tr {
            border-bottom: 1px solid #e9ecef;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        /* Logout Button */
        .logout-section {
            text-align: center;
            margin-top: 30px;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            border: none;
            border-radius: 12px;
            padding: 15px 50px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(255,107,107,0.3);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255,107,107,0.4);
        }

        @media (max-width: 768px) {
            .charts-section { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <i class="fas fa-store"></i>
            <div class="logo-text">APOTEK<br>PEMILIK</div>
        </div>
        <div class="header-right">
            <div class="user-info">
                <div class="name">{{ auth()->user()->name ?? 'Pemilik Apotek' }}</div>
                <div class="role">Pemilik</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="welcome-icon">
                <i class="fas fa-user-md"></i>
            </div>
            <h1>Selamat Datang!!</h1>
            <p>Monitoring dan kontrol sistem apotek</p>
        </div>

        <!-- User Info Card -->
        <div class="user-info-card">
            <div class="user-avatar">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="user-details">
                <h3>{{ auth()->user()->name ?? 'Pemilik Apotek' }}</h3>
                <p>
                    <i class="fas fa-calendar"></i>
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card purple">
                <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-value">Rp 0</div>
                <div class="stat-label">Pendapatan Hari Ini</div>
            </div>
            <div class="stat-card pink">
                <div class="stat-icon"><i class="fas fa-receipt"></i></div>
                <div class="stat-value">0</div>
                <div class="stat-label">Transaksi Hari Ini</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-value">0</div>
                <div class="stat-label">Total Pelanggan</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                <div class="stat-value">0</div>
                <div class="stat-label">Total Produk</div>
            </div>
        </div>

        <!-- Menu Cards -->
        <div class="menu-cards-grid">
            <a href="{{ route('pemilik.stok-obat') }}" class="menu-card purple">
                <div class="menu-card-icon"><i class="fas fa-warehouse"></i></div>
                <div class="menu-card-title">Kelola Obat</div>
            </a>

            <a href="{{ route('pemilik.laporan') }}" class="menu-card pink">
                <div class="menu-card-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="menu-card-title">Laporan Keuangan</div>
            </a>
        </div>

        <!-- Chart Pendapatan -->
        <div class="charts-section">
            <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-chart-area" style="color: #667eea;"></i>
                    Grafik Pendapatan Bulanan
                </div>
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>

        <!-- Activity Table -->
        <div class="activity-card">
            <div class="chart-title">
                <i class="fas fa-clock" style="color: #ffd89b;"></i>
                Aktivitas Terbaru
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #95a5a6;">Belum ada aktivitas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Logout -->
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> LOGOUT
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik Pendapatan
        const revenueCtx = document.getElementById('revenueChart');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan (Juta Rupiah)',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                }
            }
        });
    </script>
</body>
</html>
