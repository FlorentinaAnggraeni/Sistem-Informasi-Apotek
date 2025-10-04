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
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
    }

    /* Bagian gambar landing */
    .hero-section {
      background: url('/images/landing.png') no-repeat center center/cover;
      height: 100vh;           /* Full tinggi layar */
      width: 100%;
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

<!-- NAVBAR -->
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

<!-- HERO (Gambar Fullscreen) -->
<section class="hero-section"></section>

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
