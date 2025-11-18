<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan / Apoteker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #e8f0f7; overflow-x: hidden; }
        .main-content { padding: 40px; min-height: 100vh; }

        /* Welcome Banner */
        .welcome-banner { background: linear-gradient(135deg, #ffd89b 0%, #ffbe76 100%); border-radius: 20px; padding: 50px; margin-bottom: 30px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1);}
        .welcome-icon { width: 100px; height: 100px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);}
        .welcome-icon i { font-size: 3.5rem; color: #00bcd4;}
        .welcome-banner h1 { font-size: 2.5rem; font-weight: 700; color: #2c3e50; margin-bottom: 8px;}
        .welcome-banner p { font-size: 1.15rem; color: #34495e; font-weight: 500;}

        /* User Info Card */
        .user-info-card { background: white; border-radius: 20px; padding: 25px 35px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 20px;}
        .user-avatar { width: 70px; height: 70px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;}
        .user-details h3 { font-size: 1.5rem; color: #2c3e50; margin-bottom: 5px; font-weight: 600;}
        .user-details p { color: #7f8c8d; font-size: 0.95rem; display: flex; align-items: center; gap: 5px;}

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;}
        .stat-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border: 3px solid; transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15);}
        .stat-card.purple { border-color: #667eea; }
        .stat-card.blue { border-color: #4facfe; }
        .stat-card.green { border-color: #43e97b; }
        .stat-icon { width: 65px; height: 65px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; font-size: 2rem; color: white; }
        .stat-card.purple .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);}
        .stat-card.blue .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);}
        .stat-card.green .stat-icon { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);}
        .stat-value { font-size: 1.8rem; font-weight: 700; color: #2c3e50; margin-bottom: 5px;}
        .stat-label { color: #7f8c8d; font-size: 0.95rem; font-weight: 500;}

        /* Menu Cards Grid */
        .menu-cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 30px;}
        .menu-card { background: white; border-radius: 15px; padding: 35px 25px; text-align: center; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border: 3px solid;}
        .menu-card:hover { transform: translateY(-8px); box-shadow: 0 10px 25px rgba(0,0,0,0.15);}
        .menu-card.orange { border-color: #ffd89b; }
        .menu-card.purple { border-color: #667eea; }
        .menu-card.blue { border-color: #4facfe; }
        .menu-card-icon { width: 80px; height: 80px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; border-radius: 18px;}
        .menu-card.orange .menu-card-icon { background: linear-gradient(135deg, #ffd89b 0%, #ff9a56 100%);}
        .menu-card.purple .menu-card-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);}
        .menu-card.blue .menu-card-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);}
        .menu-card-icon i { font-size: 2.5rem; color: white; }
        .menu-card-title { font-size: 1.05rem; font-weight: 600; color: #2c3e50; }

        /* Orders Table */
        .orders-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 30px; }
        .orders-title { font-size: 1.2rem; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8f9fa; }
        th { padding: 12px 15px; text-align: left; font-weight: 600; color: #2c3e50; font-size: 0.9rem;}
        td { padding: 12px 15px; color: #7f8c8d; font-size: 0.9rem; }
        tr { border-bottom: 1px solid #e9ecef; }
        tbody tr:hover { background: #f8f9fa; }

        /* Logout */
        .logout-section { text-align: center; margin-top: 30px; }
        .logout-btn { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); border: none; border-radius: 12px; padding: 15px 50px; font-size: 1.1rem; font-weight: 600; color: white; cursor: pointer; box-shadow: 0 4px 15px rgba(255,107,107,0.3); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;}
        .logout-btn:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(255,107,107,0.4); }

        @media (max-width:768px){ .main-content{padding:20px;} .stats-grid,.menu-cards-grid{grid-template-columns:1fr;} .welcome-banner{padding:30px 20px;} .welcome-banner h1{font-size:2rem;} }
    </style>
</head>
<body>
<div class="main-content">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-icon"><i class="fas fa-user-md"></i></div>
        <h1>Selamat Datang!!</h1>
        <p>Kelola transaksi dan pesanan pelanggan</p>
    </div>

    <!-- User Info -->
    <div class="user-info-card">
        <div class="user-avatar"><i class="fas fa-user"></i></div>
        <div class="user-details">
            <h3>{{ auth()->user()->name ?? 'Apoteker' }}</h3>
            <p><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
            <div class="stat-value">0</div>
            <div class="stat-label">Pesanan Baru</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-box"></i></div>
            <div class="stat-value">0</div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value">0</div>
            <div class="stat-label">Selesai Hari Ini</div>
        </div>
    </div>

    <!-- Menu Cards -->
    <div class="menu-cards-grid">
        <a href="{{ route('apoteker.karyawan.kelola-obat') }}" class="menu-card orange">
    <div class="menu-card-icon"><i class="fas fa-capsules"></i></div>
    <div class="menu-card-title">Kelola Obat</div>
</a>

<a href="{{ route('apoteker.karyawan.transaksi') }}" class="menu-card purple">
    <div class="menu-card-icon"><i class="fas fa-cash-register"></i></div>
    <div class="menu-card-title">Buat Transaksi Baru</div>
</a>

<a href="{{ route('apoteker.karyawan.pesanan-masuk') }}" class="menu-card blue">
    <div class="menu-card-icon"><i class="fas fa-inbox"></i></div>
    <div class="menu-card-title">Lihat Pesanan Masuk</div>
</a>

    </div>

    <!-- Orders Table -->
    <div class="orders-card">
        <div class="orders-title"><i class="fas fa-list" style="color:#667eea;"></i> Pesanan Terbaru</div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" style="text-align:center; color:#95a5a6;">Belum ada pesanan</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Logout -->
    <div class="logout-section">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> LOGOUT</button>
        </form>
    </div>
</div>
</body>
</html>
