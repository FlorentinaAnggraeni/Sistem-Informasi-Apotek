@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(145deg, #cce4ff, #a7d0ff);
        min-height: 100vh;
    }

    .transaksi-card {
        background: #ffffff;
        padding: 25px;
        border-radius: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: fadeIn 0.4s ease-in-out;
    }

    h3 {
        color: #0d47a1;
        font-weight: 700;
    }

    .card-total {
        background: #e3f2fd;
        border-radius: 15px;
        border-left: 5px solid #0d47a1;
    }

    .btn-primary {
        background: #0d47a1;
        border: none;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background: #08306b;
    }

    .btn-success {
        background: #2e7d32;
        border: none;
        transition: 0.3s;
    }

    .btn-danger {
        border-radius: 10px;
        font-weight: bold;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>

<div class="container mt-4">

    <div class="transaksi-card">

        <h3 class="mb-4">Buat Transaksi Baru</h3>

        <form action="{{ route('apoteker.transaksi.store') }}" method="POST">
            @csrf

            <div id="list-obat">

                <div class="row mb-3 obat-item">

                    <div class="col-md-5">
                        <label class="form-label fw-bold">Pilih Obat</label>
                        <select name="obat_id[]" class="form-select obat-select shadow-sm" required>
                            <option value="">-- Pilih Obat --</option>
                            @foreach($obat as $o)
                                <option value="{{ $o->id }}" data-harga="{{ $o->harga }}">
                                    {{ $o->nama_obat }} - Rp {{ number_format($o->harga) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Jumlah</label>
                        <input type="number" class="form-control jumlah-input shadow-sm" 
                               name="jumlah[]" min="1" value="1" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Subtotal</label>
                        <input type="text" class="form-control subtotal-view shadow-sm" disabled>
                    </div>

                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100 remove-obat">×</button>
                    </div>

                </div>

            </div>

            <button type="button" id="tambahObat" class="btn btn-primary mb-3 px-3">
                + Tambah Obat
            </button>

            <div class="card p-3 mb-3 card-total">
                <h5 class="fw-bold">TOTAL: 
                    <span id="grandTotal" class="text-primary">Rp 0</span>
                </h5>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2" style="border-radius:12px;">
                Simpan Transaksi
            </button>

        </form>

    </div>

</div>

<script>
function hitungSubtotal(item){
    let harga = parseInt(item.querySelector('.obat-select').selectedOptions[0]?.dataset?.harga || 0);
    let jumlah = parseInt(item.querySelector('.jumlah-input').value || 0);
    let subtotal = harga * jumlah;
    item.querySelector('.subtotal-view').value = 'Rp ' + subtotal.toLocaleString();
    return subtotal;
}

function hitungTotal(){
    let total = 0;
    document.querySelectorAll('.obat-item').forEach(i => total += hitungSubtotal(i));
    document.getElementById('grandTotal').innerText = 'Rp ' + total.toLocaleString();
}

document.addEventListener('change', e => {
    if(e.target.classList.contains('obat-select') || e.target.classList.contains('jumlah-input')){
        hitungTotal();
    }
});

document.getElementById('tambahObat').addEventListener('click', () => {
    let clone = document.querySelector('.obat-item').cloneNode(true);
    clone.querySelector('.subtotal-view').value = '';
    clone.querySelector('.jumlah-input').value = 1;
    document.getElementById('list-obat').appendChild(clone);
});

document.addEventListener('click', e => {
    if(e.target.classList.contains('remove-obat')){
        if(document.querySelectorAll('.obat-item').length > 1){
            e.target.closest('.obat-item').remove();
            hitungTotal();
        }
    }
});

hitungTotal();
</script>

@endsection
