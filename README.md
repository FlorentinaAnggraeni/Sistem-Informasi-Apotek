# 💊 Sistem Informasi Apotek

Sistem Informasi Apotek adalah aplikasi web berbasis Laravel untuk mengelola operasional apotek, mulai dari manajemen obat, pemesanan, resep dokter, hingga transaksi dan laporan.

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Role & Akses](#-role--akses)
- [Flow Sistem](#-flow-sistem)
- [Instalasi](#-instalasi)
- [Teknologi](#-teknologi)
- [Workflow Kolaborasi](#-workflow-kolaborasi)

---

## ✨ Fitur Utama

### 🔹 Manajemen Obat
- CRUD obat dengan kategori dan supplier
- Monitoring stok obat (habis, menipis, aman)
- Update stok secara real-time
- Upload gambar obat
- Tanggal kadaluarsa & nomor batch

### 🔹 Pemesanan Obat
- **Pembelian Langsung**: Pelanggan dapat membeli obat bebas tanpa resep
- **Pembelian dengan Resep**: Upload foto resep dokter untuk obat yang memerlukan resep
- Keranjang belanja
- Multiple payment methods (Transfer Bank, E-Wallet, COD)
- Upload bukti pembayaran
- Tracking status pesanan

### 🔹 Resep Dokter
- Upload foto resep dokter
- Validasi resep oleh apoteker
- Proses resep (pilih obat, tentukan jumlah)
- Catatan apoteker untuk pelanggan
- Status tracking (Pending → Diproses → Selesai/Ditolak)

### 🔹 Transaksi & Laporan
- Verifikasi pembayaran
- Konfirmasi pengiriman
- Laporan penjualan, stok, pelanggan, keuangan
- Export PDF & Excel

### 🔹 Notifikasi Real-time
- Notifikasi pesanan baru
- Update status pesanan
- Stok obat menipis/habis
- Resep dokter baru

---

## 👥 Role & Akses

### 1️⃣ Pelanggan
**Akses:**
- Lihat & beli produk obat
- Upload resep dokter
- Keranjang belanja
- Checkout & pembayaran
- Tracking pesanan
- Upload bukti pembayaran
- Konfirmasi penerimaan barang
- Lihat riwayat resep
- Kelola profil

### 2️⃣ Apoteker
**Akses:**
- Kelola obat (CRUD)
- Kelola kategori obat
- Kelola supplier
- Monitoring & update stok obat
- Validasi & proses resep dokter
- Lihat pesanan pelanggan
- Verifikasi pembayaran

### 3️⃣ Karyawan
**Akses:**
- Kelola pesanan
- Verifikasi pembayaran
- Konfirmasi pengiriman
- Lihat transaksi
- Laporan harian

### 4️⃣ Pemilik
**Akses:**
- Dashboard analytics
- Kelola pelanggan
- Kelola karyawan
- Laporan lengkap (penjualan, stok, pelanggan, keuangan)
- Export laporan (PDF & Excel)
- Pengaturan sistem

---

## 🔄 Flow Sistem

### 📦 Alur Pemesanan Obat Langsung (Tanpa Resep)

```
1. Pelanggan Browse Produk
   ↓
2. Tambah ke Keranjang
   ↓
3. Checkout (Pilih alamat & metode pembayaran)
   ↓
4. Buat Pesanan (Status: Menunggu Pembayaran)
   ↓
5. Upload Bukti Pembayaran (Status: Menunggu Verifikasi)
   ↓
6. Karyawan/Apoteker Verifikasi Pembayaran
   ├─ Ditolak → Pelanggan upload ulang
   └─ Diverifikasi → Status: Diproses
       ↓
7. Karyawan Proses Pesanan (Status: Dikirim)
   ↓
8. Pelanggan Konfirmasi Penerimaan (Status: Selesai)
   ↓
9. Sistem Buat Transaksi Otomatis
```

### 💊 Alur Pemesanan dengan Resep Dokter

```
1. Pelanggan Upload Resep Dokter
   - Upload foto resep
   - Isi nama pasien & keluhan
   ↓
2. Sistem Simpan Resep (Status: Pending)
   ↓
3. Apoteker Terima Notifikasi Resep Baru
   ↓
4. Apoteker Validasi Resep
   ├─ Ditolak → Pelanggan dapat upload ulang
   └─ Diproses → Apoteker pilih obat & jumlah
       ↓
5. Apoteker Tambah Obat ke Resep
   - Pilih obat dari stok
   - Tentukan jumlah
   - Tambah catatan (opsional)
   ↓
6. Update Status Resep (Status: Selesai)
   ↓
7. Pelanggan Lihat Detail Resep
   - Lihat obat yang diresepkan
   - Lihat catatan apoteker
   - Timeline proses resep
   ↓
8. [OPSIONAL] Pelanggan Beli Obat dari Resep
   - Tambah obat ke keranjang
   - Lanjut ke proses pemesanan normal
```

### 🔍 Detail Status Pesanan

| Status | Deskripsi | Aksi Pelanggan | Aksi Karyawan/Apoteker |
|--------|-----------|----------------|------------------------|
| **Menunggu Pembayaran** | Pesanan dibuat, belum bayar | Upload bukti bayar | - |
| **Menunggu Verifikasi** | Bukti bayar sudah diupload | Tunggu verifikasi | Verifikasi pembayaran |
| **Diproses** | Pembayaran diverifikasi | Tunggu pengiriman | Proses & kirim pesanan |
| **Dikirim** | Pesanan dalam pengiriman | Konfirmasi penerimaan | Update status |
| **Selesai** | Pesanan diterima pelanggan | - | - |
| **Dibatalkan** | Pesanan dibatalkan | - | - |

### 🩺 Detail Status Resep

| Status | Deskripsi | Aksi Pelanggan | Aksi Apoteker |
|--------|-----------|----------------|---------------|
| **Pending** | Resep baru diupload | Tunggu validasi | Validasi resep |
| **Diproses** | Apoteker sedang proses | Tunggu selesai | Pilih obat & jumlah |
| **Selesai** | Resep sudah diproses | Lihat detail obat | - |
| **Ditolak** | Resep ditolak apoteker | Upload ulang | - |

### 📊 Alur Laporan (Pemilik)

```
1. Pemilik Akses Menu Laporan
   ↓
2. Pilih Jenis Laporan:
   ├─ Laporan Penjualan (per periode)
   ├─ Laporan Stok Obat (status stok)
   ├─ Laporan Pelanggan (data pelanggan)
   └─ Laporan Keuangan (pemasukan)
   ↓
3. Filter Data (tanggal, kategori, dll)
   ↓
4. Lihat/Preview Laporan
   ↓
5. Export Laporan
   ├─ PDF
   └─ Excel
```

---

## 🚀 Instalasi

### Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL/MariaDB
- Node.js & NPM (untuk asset compilation)

### Langkah Instalasi

#### 1️⃣ Clone Repository
```bash
git clone https://github.com/FlorentinaAnggraeni/Sistem-Informasi-Apotek.git
cd Sistem-Informasi-Apotek
```

#### 2️⃣ Install Dependencies
```bash
composer install
npm install
```

#### 3️⃣ Konfigurasi Environment
```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_apotik
DB_USERNAME=root
DB_PASSWORD=
```

#### 4️⃣ Generate Application Key
```bash
php artisan key:generate
```

#### 5️⃣ Jalankan Migration & Seeder
```bash
php artisan migrate
php artisan db:seed
```

#### 6️⃣ Create Storage Link
```bash
php artisan storage:link
```

#### 7️⃣ Compile Assets
```bash
npm run dev
# atau untuk production:
npm run build
```

#### 8️⃣ Jalankan Aplikasi
```bash
php artisan serve
```

Akses aplikasi di: `http://127.0.0.1:8000`

### 👤 Default User Login

Setelah menjalankan seeder, gunakan kredensial berikut:

| Role | Email | Password |
|------|-------|----------|
| Pemilik | pemilik@apotek.com | password |
| Apoteker | apoteker@apotek.com | password |
| Karyawan | karyawan@apotek.com | password |
| Pelanggan | pelanggan@apotek.com | password |

---

## 🛠️ Teknologi

### Backend
- **Framework**: Laravel 12.31.1
- **Language**: PHP 8.2.12
- **Database**: MySQL
- **Authentication**: Laravel Breeze/Sanctum
- **File Storage**: Laravel Storage

### Frontend
- **Template Engine**: Blade
- **CSS Framework**: Bootstrap 5
- **Icons**: Font Awesome
- **JavaScript**: Vanilla JS & Alpine.js

### Libraries
- **Image Handling**: Intervention Image
- **PDF Export**: DomPDF / Laravel PDF
- **Excel Export**: Maatwebsite Excel
- **Notifications**: Laravel Notifications

---

## 🔹 Workflow Kolaborasi

### a. Update repo dulu sebelum ngoding
```bash
git pull origin main
```

### b. Bikin branch baru untuk fitur/bugfix
```bash
git checkout -b fitur-nama-fitur
```

### c. Coding → commit → push ke branch
```bash
git add .
git commit -m "Deskripsi perubahan"
git push origin fitur-nama-fitur
```

### d. Bikin Pull Request (PR)
- Dari GitHub → buat PR dari `fitur-nama-fitur` ke `main`
- Tim lain review & merge

---

## 📝 Catatan Penting

### ⚠️ Jangan Upload ke GitHub:
- File `.env`
- Folder `vendor/`
- Folder `node_modules/`
- File upload di `storage/app/public/`

### 🔄 Setelah Git Pull:
Jika ada perubahan migration atau dependencies:
```bash
composer install
npm install
php artisan migrate
```

---

## 📂 Struktur Database

### Tabel Utama:
- **users**: Data user (semua role)
- **pelanggans**: Data pelanggan
- **karyawans**: Data karyawan
- **pemiliks**: Data pemilik
- **kategoris**: Kategori obat
- **suppliers**: Data supplier
- **obats**: Data obat
- **keranjangs**: Keranjang belanja
- **pesanans**: Pesanan
- **detail_pesanans**: Detail item pesanan
- **transaksis**: Transaksi pembayaran
- **reseps**: Resep dokter
- **detail_reseps**: Detail obat dalam resep
- **notifications**: Notifikasi sistem

---

## 🤝 Kontributor

- **Florentina Anggraeni** - Developer

---

## 📄 Lisensi

Project ini dibuat untuk keperluan edukasi dan pembelajaran.

---

## 📞 Kontak & Support

Jika ada pertanyaan atau issue, silakan buat issue di GitHub repository atau hubungi developer.

---

**Happy Coding! 💻💊**