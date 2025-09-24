# Workflow Kolaborasi dengan Tim

## 🚀 Setup Awal

### 1. Clone repo dari GitHub
```bash
git clone https://github.com/username/repo.git
cd repo
```

### 2. Install dependencies
```bash
composer install
npm install && npm run dev   # kalau pakai vite/laravel mix
```

### 3. Copy file `.env`
Minta file `.env` dari leader (⚠️ **jangan diupload ke GitHub**).

```bash
cp .env.example .env
```

Lalu isi setting database masing-masing.

### 4. Generate app key
```bash
php artisan key:generate
```

### 5. Migrate database
```bash
php artisan migrate
```

---

## 🔹 Cara Kerja Sehari-hari (Kolaborasi)

### a. Update repo dulu sebelum ngoding
```bash
git pull origin main
```

### b. Bikin branch baru untuk fitur/bugfix
```bash
git checkout -b fitur-auth
```

### c. Coding → commit → push ke branch
```bash
git add .
git commit -m "nambah fitur login"
git push origin fitur-auth
```

### d. Bikin Pull Request (PR)
- Dari GitHub → buat PR dari `fitur-auth` ke `main`.  
- Tim lain review & merge.

---

## 🔹 Catatan Penting Laravel + GitHub

- Jangan upload file `.env` ke GitHub.  
- Folder `vendor` tidak usah dipush → tiap anggota cukup jalankan `composer install`.  
- Kalau ada perubahan migration, setelah `git pull`, anggota lain wajib jalankan:
  ```bash
  php artisan migrate
  ```
