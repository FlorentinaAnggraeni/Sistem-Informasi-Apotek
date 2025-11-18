@extends('layouts.app')

@section('title', 'Pesanan Masuk')

@section('content')
<h1>Pesanan Masuk</h1>
@if($pesanans->isEmpty())
    <p>Tidak ada pesanan baru.</p>
@else
    <table class="table">
        <thead>
            <tr>
                <th>No</th><th>Nama Pelanggan</th><th>Obat</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesanans as $key => $pesanan)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $pesanan->user->name }}</td>
                <td>{{ $pesanan->obat->nama_obat }}</td>
                <td>{{ $pesanan->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
