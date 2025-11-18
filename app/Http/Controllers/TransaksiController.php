<?php
namespace App\Http\Controllers;


use App\Models\Obat;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;


class TransaksiController extends Controller
{
public function create()
{
$obat = Obat::all();
return view('transaksi.create', compact('obat'));
}


public function store(Request $request)
{
$request->validate([
'obat_id' => 'required|array',
'jumlah' => 'required|array'
]);


$total = 0;
foreach ($request->obat_id as $i => $id) {
$harga = Obat::find($id)->harga;
$qty = $request->jumlah[$i];
$total += $harga * $qty;
}


$transaksi = Transaksi::create(['total_harga' => $total]);


foreach ($request->obat_id as $i => $id) {
$harga = Obat::find($id)->harga;
$qty = $request->jumlah[$i];


TransaksiDetail::create([
'transaksi_id' => $transaksi->id,
'obat_id' => $id,
'jumlah' => $qty,
'subtotal' => $harga * $qty,
]);
}


return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');
}
}