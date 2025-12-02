@extends('layouts.dashboard')
@section('title', 'Detail Pesanan')

@section('sidebar-menu')
<li>
    <a href="{{ route('dashboard') }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('karyawan.transaksi.index') }}">
        <i class="fas fa-cash-register"></i>
        <span>Transaksi</span>
    </a>
</li>
<li>
    <a href="{{ route('karyawan.pesanan.index') }}" class="active">
        <i class="fas fa-inbox"></i>
        <span>Pesanan</span>
    </a>
</li>
<li>
    <a href="{{ route('karyawan.obat.index') }}">
        <i class="fas fa-pills"></i>
        <span>Daftar Obat</span>
    </a>
</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Detail Pesanan</h2>
        <p class="text-muted">Informasi detail pesanan pelanggan</p>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Detail Pesanan</h5>
        <a href="{{ route('karyawan.pesanan.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Informasi Pesanan</h6>
                <table class="table table-sm">
                    <tr>
                        <td width="150">Kode Pesanan</td>
                        <td><code>{{ $pesanan->kode_pesanan }}</code></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>{{ $pesanan->tanggal_pesanan->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Status Pembayaran</td>
                        <td>
                            @if($pesanan->status_pembayaran == 'paid')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status Pesanan</td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($pesanan->status_pesanan) }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Informasi Pelanggan</h6>
                <table class="table table-sm">
                    <tr>
                        <td width="150">Nama</td>
                        <td>{{ $pesanan->pelanggan->nama_pelanggan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>{{ $pesanan->pelanggan->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>No. Telepon</td>
                        <td>{{ $pesanan->pelanggan->no_telepon ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Bukti Pembayaran & Verifikasi -->
        @if($pesanan->metode_pembayaran && in_array($pesanan->metode_pembayaran, ['transfer', 'e-wallet']))
            <div class="row mb-4">
                <div class="col-12">
                    <h6><i class="fas fa-receipt"></i> Bukti Pembayaran</h6>
                    <div class="card border-{{ $pesanan->status_pembayaran == 'paid' ? 'success' : 'warning' }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Metode Pembayaran:</strong> 
                                        <span class="badge bg-info">{{ strtoupper($pesanan->metode_pembayaran) }}</span>
                                    </p>
                                    @if($pesanan->va_number)
                                        <p class="mb-2"><strong>Virtual Account:</strong> 
                                            <code>{{ $pesanan->va_number }}</code>
                                        </p>
                                    @endif
                                    <p class="mb-2"><strong>Status:</strong> 
                                        @if($pesanan->status_pembayaran == 'paid')
                                            <span class="badge bg-success">Terverifikasi</span>
                                        @elseif($pesanan->status_pembayaran == 'pending')
                                            <span class="badge bg-warning">Menunggu Verifikasi</span>
                                        @else
                                            <span class="badge bg-danger">Belum Bayar</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    @if($pesanan->bukti_pembayaran)
                                        <div class="text-center">
                                            <p class="mb-2"><strong>Bukti Transfer/Screenshot:</strong></p>
                                            @php
                                                $extension = pathinfo($pesanan->bukti_pembayaran, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                                <a href="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" 
                                                         class="img-thumbnail" 
                                                         style="max-height: 200px; cursor: pointer;"
                                                         alt="Bukti Pembayaran">
                                                </a>
                                                <p class="small text-muted mt-1">Klik untuk memperbesar</p>
                                            @elseif($extension == 'pdf')
                                                <a href="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" target="_blank" class="btn btn-primary">
                                                    <i class="fas fa-file-pdf fa-3x d-block mb-2"></i>
                                                    Lihat Bukti PDF
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle"></i> Pelanggan belum mengupload bukti pembayaran
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($pesanan->bukti_pembayaran && $pesanan->status_pembayaran == 'pending')
                                <hr>
                                <div class="text-center">
                                    <p class="mb-3"><strong>Verifikasi Pembayaran:</strong></p>
                                    <form action="{{ route('karyawan.pesanan.verifikasi', $pesanan) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="is_approved" value="1">
                                        <button type="submit" class="btn btn-success me-2" onclick="return confirm('Yakin ingin menyetujui pembayaran ini?')">
                                            <i class="fas fa-check-circle"></i> Setujui Pembayaran
                                        </button>
                                    </form>
                                    <form action="{{ route('karyawan.pesanan.verifikasi', $pesanan) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="is_approved" value="0">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menolak pembayaran ini?')">
                                            <i class="fas fa-times-circle"></i> Tolak Pembayaran
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Update Status Pengiriman -->
        @if($pesanan->status_pembayaran == 'paid')
            <div class="row mb-4">
                <div class="col-12">
                    <h6><i class="fas fa-shipping-fast"></i> Update Status Pengiriman</h6>
                    <div class="card border-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>Status Saat Ini:</strong>
                                    @if($pesanan->status_pengiriman == 'pending')
                                        <span class="badge bg-warning text-dark">Pending - Menunggu Diproses</span>
                                    @elseif($pesanan->status_pengiriman == 'shipped')
                                        <span class="badge bg-info">Dikirim - Dalam Perjalanan</span>
                                    @elseif($pesanan->status_pengiriman == 'delivered')
                                        <span class="badge bg-success">Selesai - Telah Diterima</span>
                                    @else
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </div>
                            </div>

                            @if($pesanan->status_pengiriman == 'pending')
                                <!-- Tombol: Proses Pesanan -->
                                <form action="{{ route('karyawan.pesanan.update-status', $pesanan) }}" method="POST" class="mb-3">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_pengiriman" value="shipped">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label class="form-label">Nomor Resi Pengiriman:</label>
                                            <input type="text" name="no_resi" class="form-control" placeholder="Masukkan nomor resi" required>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Yakin ingin mengubah status ke Dikirim?')">
                                                <i class="fas fa-shipping-fast"></i> Kirim Pesanan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @elseif($pesanan->status_pengiriman == 'shipped')
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>No. Resi:</strong> {{ $pesanan->no_resi ?? '-' }} | 
                                    <strong>Tanggal Kirim:</strong> {{ $pesanan->tanggal_pengiriman ? $pesanan->tanggal_pengiriman->format('d/m/Y H:i') : '-' }}
                                    <br>
                                    <small class="text-muted">Pesanan sedang dalam perjalanan. Menunggu konfirmasi penerimaan dari pelanggan.</small>
                                </div>
                            @elseif($pesanan->status_pengiriman == 'delivered')
                                <div class="alert alert-success mb-0">
                                    <i class="fas fa-check-circle"></i> 
                                    Pesanan telah selesai dan diterima oleh pelanggan.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <h6>Detail Item</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan->detailPesanans as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->obat->nama_obat }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="4" class="text-end">TOTAL:</td>
                        <td>Rp {{ number_format($pesanan->total_nota, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($pesanan->status_pembayaran == 'pending')
            <div class="mt-4">
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Pembayaran Menunggu Konfirmasi</strong>
                    <p class="mb-0 mt-2">Silakan konfirmasi pembayaran sebelum memproses transaksi.</p>
                </div>
                <form action="{{ route('karyawan.pesanan.verifikasi', $pesanan) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                    </button>
                </form>
                <a href="{{ route('karyawan.pesanan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        @elseif($pesanan->status_pembayaran == 'paid')
            <div class="mt-4">
                <a href="{{ route('karyawan.transaksi.create', $pesanan) }}" class="btn btn-success">
                    <i class="fas fa-cash-register"></i> Proses Transaksi
                </a>
            </div>
        @else
            <div class="mt-4">
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-times-circle"></i> 
                    <strong>Pembayaran Ditolak/Gagal</strong>
                    <p class="mb-0 mt-2">Pesanan ini tidak dapat diproses karena pembayaran ditolak.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
