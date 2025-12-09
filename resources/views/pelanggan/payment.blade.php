@extends('layouts.dashboard')
@section('title', 'Pembayaran')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.produk') }}">
        <i class="fas fa-capsules"></i>
        <span>Produk Obat</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.keranjang') }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Keranjang</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.pesanan') }}">
        <i class="fas fa-box"></i>
        <span>Pesanan Saya</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.resep.index') }}">
        <i class="fas fa-file-prescription"></i>
        <span>Resep Dokter</span>
    </a>
</li>
<li>
    <a href="{{ route('pelanggan.profil') }}">
        <i class="fas fa-user"></i>
        <span>Profil Saya</span>
    </a>
</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Pembayaran</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.pesanan') }}">Pesanan</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.pesanan.show', $pesanan) }}">Detail</a></li>
                <li class="breadcrumb-item active">Pembayaran</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        @if($paymentInstructions)
            <div class="card-dashboard mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-credit-card text-primary"></i> {{ $paymentInstructions['title'] }}
                </h5>

                @if($pesanan->metode_pembayaran == 'transfer' || !$pesanan->metode_pembayaran)
                    <!-- Transfer Bank -->
                    @if(isset($paymentInstructions['banks']))
                        <h6 class="mb-3">Pilih Bank Tujuan:</h6>
                        <div class="row mb-4">
                            @foreach($paymentInstructions['banks'] as $bank)
                                <div class="col-md-4 mb-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <h5 class="text-primary fw-bold mb-2">{{ $bank['name'] }}</h5>
                                            <div class="bg-light p-2 rounded mb-2">
                                                <h4 class="mb-0 font-monospace">{{ $bank['account'] }}</h4>
                                            </div>
                                            <small class="text-muted">a.n {{ $bank['holder'] }}</small>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-outline-secondary" data-account="{{ $bank['account'] }}" onclick="copyToClipboard(this.dataset.account)">
                                                    <i class="fas fa-copy"></i> Salin
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Total yang harus ditransfer:</strong> 
                            <h4 class="mt-2 mb-0 text-dark">Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</h4>
                        </div>
                    @endif

                    <!-- Upload Bukti Transfer -->
                    @if($pesanan->status_pembayaran != 'paid')
                        <div class="card border-primary">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-upload text-primary"></i> Upload Bukti Transfer</h6>
                                <p class="text-muted small">Setelah melakukan transfer, silakan upload bukti pembayaran Anda di sini.</p>
                                
                                @if($pesanan->bukti_pembayaran)
                                    <div class="alert alert-success mb-3">
                                        <i class="fas fa-check-circle"></i> Bukti pembayaran sudah diupload. Menunggu verifikasi admin.
                                        <div class="mt-2">
                                            @php
                                                $extension = pathinfo($pesanan->bukti_pembayaran, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                                <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" class="img-thumbnail" style="max-height: 200px;">
                                            @elseif($extension == 'pdf')
                                                <a href="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-file-pdf"></i> Lihat PDF
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('pelanggan.pesanan.upload-bukti', $pesanan) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">File Bukti Transfer <span class="text-danger">*</span></label>
                                        <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*,application/pdf" required>
                                        <small class="text-muted">Format: JPG, PNG, PDF (Max: 2MB)</small>
                                        @error('bukti_pembayaran')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> {{ $pesanan->bukti_pembayaran ? 'Upload Ulang' : 'Upload Bukti' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Pembayaran Anda telah diverifikasi dan dikonfirmasi.
                        </div>
                    @endif

                @elseif($pesanan->metode_pembayaran == 'e-wallet')
                    <!-- E-Wallet -->
                    <div class="card border-success mb-3">
                        <div class="card-body">
                            <h5 class="mb-3 text-center">Pembayaran E-Wallet</h5>
                            
                            @if($pesanan->va_number)
                                <div class="alert alert-info text-center">
                                    <p class="mb-2"><strong>Nomor Virtual Account:</strong></p>
                                    <div class="bg-white p-3 rounded">
                                        <h3 class="mb-0 font-monospace text-dark">{{ $pesanan->va_number }}</h3>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary mt-2" data-va="{{ $pesanan->va_number }}" onclick="copyToClipboard(this.dataset.va)">
                                        <i class="fas fa-copy"></i> Salin Nomor VA
                                    </button>
                                </div>
                            @endif

                            <div class="text-center mb-3">
                                <p class="text-muted">Atau gunakan QR Code</p>
                                <div class="qr-code-placeholder bg-light p-4 mb-3">
                                    <i class="fas fa-qrcode fa-5x text-muted"></i>
                                    <p class="mt-3 text-muted small">Scan QR Code dengan aplikasi E-Wallet Anda<br>(GoPay, OVO, DANA, ShopeePay)</p>
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Total pembayaran:</strong> 
                                <h4 class="mt-2 mb-0 text-dark">Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Bukti E-Wallet -->
                    @if($pesanan->status_pembayaran != 'paid')
                        <div class="card border-primary">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-upload text-primary"></i> Upload Bukti Pembayaran</h6>
                                <p class="text-muted small">Upload screenshot bukti transaksi dari aplikasi E-Wallet Anda.</p>
                                
                                @if($pesanan->bukti_pembayaran)
                                    <div class="alert alert-success mb-3">
                                        <i class="fas fa-check-circle"></i> Bukti pembayaran sudah diupload. Menunggu verifikasi admin.
                                        <div class="mt-2">
                                            @php
                                                $extension = pathinfo($pesanan->bukti_pembayaran, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                                <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" class="img-thumbnail" style="max-height: 200px;">
                                            @elseif($extension == 'pdf')
                                                <a href="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-file-pdf"></i> Lihat PDF
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('pelanggan.pesanan.upload-bukti', $pesanan) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Screenshot Bukti Pembayaran <span class="text-danger">*</span></label>
                                        <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                                        <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                                        @error('bukti_pembayaran')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> {{ $pesanan->bukti_pembayaran ? 'Upload Ulang' : 'Upload Bukti' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Pembayaran Anda telah diverifikasi dan dikonfirmasi.
                        </div>
                    @endif

                @elseif($pesanan->metode_pembayaran == 'qris')
                    <!-- QRIS Payment -->
                    <div class="card border-info mb-3">
                        <div class="card-body">
                            <h5 class="mb-4 text-center"><i class="fas fa-qrcode text-info"></i> Pembayaran QRIS</h5>
                            
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Scan QR Code di bawah menggunakan aplikasi e-wallet atau mobile banking Anda</strong>
                            </div>

                            <div class="text-center mb-4">
                                @if(isset($paymentInstructions['qr_image']))
                                    <img src="{{ $paymentInstructions['qr_image'] }}" alt="QRIS QR Code" class="img-fluid" style="max-width: 300px; border: 2px solid #00bcd4; border-radius: 10px; padding: 10px;">
                                @else
                                    <div class="qr-code-placeholder bg-light p-4 mx-auto" style="max-width: 300px; border: 2px dashed #dee2e6; border-radius: 10px;">
                                        <i class="fas fa-qrcode text-secondary" style="font-size: 80px;"></i>
                                        <p class="text-muted mt-2">QR Code QRIS</p>
                                    </div>
                                @endif
                            </div>

                            <div class="alert alert-warning text-center">
                                <i class="fas fa-money-bill"></i> 
                                <strong>Total yang harus dibayar:</strong> 
                                <h4 class="mt-2 mb-0 text-dark">Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</h4>
                            </div>

                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <p class="mb-1"><small class="text-muted">Merchant ID (MID)</small></p>
                                    <p class="font-monospace mb-0">{{ substr($pesanan->id_pesanan, 0, 16) ?? 'APOTEK2024' }}</p>
                                </div>
                            </div>

                            @if($pesanan->status_pembayaran != 'paid')
                                <div class="mt-3">
                                    <p class="text-muted small mb-2"><i class="fas fa-check"></i> Pembayaran QRIS bersifat real-time</p>
                                    <p class="text-muted small mb-2"><i class="fas fa-check"></i> Notifikasi pembayaran akan dikirim otomatis</p>
                                    <p class="text-muted small"><i class="fas fa-check"></i> Jangan tutup halaman ini sampai pembayaran selesai</p>
                                </div>
                            @else
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-check-circle"></i> Pembayaran Anda telah diverifikasi dan dikonfirmasi.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Bukti QRIS -->
                    @if($pesanan->status_pembayaran != 'paid')
                        <div class="card border-primary mt-3">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-upload text-primary"></i> Upload Bukti Pembayaran QRIS</h6>
                                <p class="text-muted small">Upload screenshot bukti transaksi QRIS Anda dari aplikasi mobile banking atau e-wallet.</p>
                                
                                @if($pesanan->bukti_pembayaran)
                                    <div class="alert alert-success mb-3">
                                        <i class="fas fa-check-circle"></i> Bukti pembayaran sudah diupload. Menunggu verifikasi admin.
                                        <div class="mt-2">
                                            @php
                                                $extension = pathinfo($pesanan->bukti_pembayaran, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                                <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" class="img-thumbnail" style="max-height: 200px;">
                                            @elseif($extension == 'pdf')
                                                <a href="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-file-pdf"></i> Lihat PDF
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('pelanggan.pesanan.upload-bukti', $pesanan) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Screenshot Bukti Pembayaran QRIS <span class="text-danger">*</span></label>
                                        <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                                        <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                                        @error('bukti_pembayaran')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> {{ $pesanan->bukti_pembayaran ? 'Upload Ulang' : 'Upload Bukti' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-success mt-3">
                            <i class="fas fa-check-circle"></i> Pembayaran Anda telah diverifikasi dan dikonfirmasi.
                        </div>
                    @endif
                @endif

                <!-- Payment Instructions -->
                @if(isset($paymentInstructions['instructions']))
                    <hr>
                    <h6 class="mb-3"><i class="fas fa-list-ol"></i> Cara Pembayaran:</h6>
                    <ol class="mb-0">
                        @foreach($paymentInstructions['instructions'] as $instruction)
                            <li class="mb-2">{{ $instruction }}</li>
                        @endforeach
                    </ol>
                @endif
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Metode pembayaran belum dipilih. Silakan kembali ke halaman checkout.
            </div>
        @endif

        <!-- Order Details -->
        <div class="card-dashboard">
            <h5 class="fw-bold mb-3"><i class="fas fa-list text-success"></i> Detail Pesanan</h5>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan->detailPesanans as $detail)
                            <tr>
                                <td>{{ $detail->obat->nama_obat }}</td>
                                <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Payment Summary -->
        <div class="card-dashboard mb-3">
            <h5 class="fw-bold mb-3"><i class="fas fa-receipt text-success"></i> Ringkasan Pembayaran</h5>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Pesanan</span>
                    <strong>Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5 class="mb-0">Total Bayar</h5>
                    <h5 class="mb-0 text-primary">Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</h5>
                </div>
            </div>

            <div class="alert alert-info small mb-3">
                <i class="fas fa-info-circle"></i> 
                <strong>Status:</strong>
                @if($pesanan->status_pembayaran == 'paid')
                    <span class="badge bg-success">Lunas</span>
                @elseif($pesanan->status_pembayaran == 'pending')
                    <span class="badge bg-warning">Menunggu Pembayaran</span>
                @else
                    <span class="badge bg-danger">Belum Bayar</span>
                @endif
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('pelanggan.pesanan.show', $pesanan) }}" class="btn btn-primary">
                    <i class="fas fa-eye"></i> Lihat Detail Pesanan
                </a>
                <a href="{{ route('pelanggan.pesanan') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Pesanan
                </a>
            </div>
        </div>

        <!-- Payment Method Info -->
        <div class="card-dashboard">
            <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-info"></i> Informasi</h6>
            <ul class="list-unstyled mb-0 small">
                <li class="mb-2"><i class="fas fa-check text-success"></i> Pastikan nominal transfer sesuai</li>
                <li class="mb-2"><i class="fas fa-check text-success"></i> Upload bukti pembayaran yang jelas</li>
                <li class="mb-2"><i class="fas fa-check text-success"></i> Verifikasi maksimal 1x24 jam</li>
                <li class="mb-2"><i class="fas fa-check text-success"></i> Hubungi CS jika ada kendala</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-dashboard {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .qr-code-placeholder {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
    }
    .font-monospace {
        font-family: 'Courier New', monospace;
        letter-spacing: 2px;
    }
</style>
@endpush

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Success feedback
        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
        btn.classList.add('btn-success');
        
        setTimeout(function() {
            btn.innerHTML = originalHtml;
            btn.classList.remove('btn-success');
        }, 2000);
    }, function(err) {
        alert('Gagal menyalin: ' + err);
    });
}
</script>
@endpush
