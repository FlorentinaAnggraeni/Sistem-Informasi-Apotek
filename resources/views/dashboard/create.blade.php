@extends('layouts.app')
<label class="form-label">Jumlah</label>
<input type="number" class="form-control jumlah-input" name="jumlah[]" min="1" value="1" required>
</div>


<div class="col-md-3">
<label class="form-label">Subtotal</label>
<input type="text" class="form-control subtotal-view" disabled>
</div>


<div class="col-md-1 d-flex align-items-end">
<button type="button" class="btn btn-danger w-100 remove-obat">×</button>
</div>
</div>
</div>


<button type="button" id="tambahObat" class="btn btn-primary mb-3">+ Tambah Obat</button>


<div class="card p-3 mb-3">
<h5 class="fw-bold">TOTAL: <span id="grandTotal">Rp 0</span></h5>
</div>


<button type="submit" class="btn btn-success w-100 py-2" style="border-radius:12px;">Simpan Transaksi</button>
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


// Event perubahan
document.addEventListener('change', e => {
if(e.target.classList.contains('obat-select') || e.target.classList.contains('jumlah-input')){
hitungTotal();
}
});


// Tambah Obat
document.getElementById('tambahObat').addEventListener('click', () => {
let clone = document.querySelector('.obat-item').cloneNode(true);
clone.querySelector('.subtotal-view').value = '';
clone.querySelector('.jumlah-input').value = 1;
document.getElementById('list-obat').appendChild(clone);
});


// Hapus Obat
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