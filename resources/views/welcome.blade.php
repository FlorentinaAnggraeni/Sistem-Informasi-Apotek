<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Apotek Cinta Farma</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    html { scroll-behavior: smooth; }
    .hero-section {
      background: linear-gradient(to bottom right, #00c6ff, #00a0b9);
      color: #fff;
      padding: 120px 0 80px 0;
      position: relative;
      overflow: hidden;
    }
    .hero-section .badge-open {
      background: #fff;
      color: #0d6efd;
      font-weight: 600;
      padding: .5rem 1rem;
      border-radius: 50rem;
      display: inline-block;
      font-size: 1.1rem;
    }
    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
      transition: all .3s ease-in-out;
    }
    footer {
      background: #212529;
      color: #ccc;
    }
    footer h5 {
      color: #fff;
    }
  </style>
</head>
<body>

<!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-dark bg-info fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Apotek <span class="text-warning">Cinta Farma</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <a href="/register" class="btn btn-warning me-2 text-dark fw-semibold">Daftar</a>
      <a href="/login" class="btn btn-light fw-semibold">Masuk</a>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero-section text-center">
  <div class="container">
    <p class="text-uppercase fw-medium">Kesehatan Anda, Prioritas Kami!</p>
    <h1 class="display-4 fw-bold mb-3">Apotek <span class="text-warning">Cinta Farma</span></h1>
    <div class="badge-open mb-2">BUKA 14,5 JAM</div>
    <p class="mb-4 fs-5">Senin - Minggu, 07.00 - 21.30</p>
    <a href="/register" class="btn btn-warning btn-lg text-dark fw-semibold shadow">Daftar Sekarang</a>
  </div>
</section>

<!-- LAYANAN (Hanya 2 Layanan) -->
<section class="py-5 bg-light" id="layanan">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-info">Layanan Kami</h2>
      <p class="text-muted">2 Layanan utama untuk kebutuhan kesehatan Anda</p>
    </div>
    <div class="row g-4 justify-content-center">

      <!-- Produk Kesehatan -->
      <div class="col-6 col-md-4 col-lg-3">
        <div class="bg-white text-center p-4 rounded service-card h-100">
          <i class="bi bi-heart-pulse text-info fs-1"></i>
          <h6 class="mt-3">Produk Kesehatan</h6>
        </div>
      </div>

      <!-- Pengantaran -->
      <div class="col-6 col-md-4 col-lg-3">
        <div class="bg-white text-center p-4 rounded service-card h-100">
          <i class="bi bi-truck text-info fs-1"></i>
          <h6 class="mt-3">Pengantaran</h6>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- CTA SECTION -->
<section class="py-5 text-center bg-info text-white">
  <div class="container">
    <h3 class="fw-bold">Bergabung dengan Kami</h3>
    <p class="fs-5">Daftar sekarang untuk kemudahan akses layanan Apotek Cinta Farma.</p>
    <a href="/register" class="btn btn-warning btn-lg text-dark fw-semibold shadow">Daftar Sekarang</a>
  </div>
</section>

<!-- FOOTER -->
<footer class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4">
        <h5>Kontak Kami</h5>
        <p>Jl. Sehat Selalu No. 12</p>
        <p>Telp: (021) 123-4567</p>
        <p>Email: info@cintafarma.com</p>
      </div>
      <div class="col-md-4 mb-4">
        <h5>Layanan</h5>
        <p>Produk Kesehatan</p>
        <p>Pengantaran</p>
      </div>
      <div class="col-md-4">
        <h5>Jam Operasional</h5>
        <p>Senin - Minggu</p>
        <p>07.00 - 21.30 (Buka 14,5 jam)</p>
      </div>
    </div>
    <hr class="border-secondary">
    <p class="text-center text-secondary m-0">&copy; 2025 Apotek Cinta Farma. All rights reserved.</p>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
