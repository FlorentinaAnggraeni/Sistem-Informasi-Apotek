@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3>Register</h3>
    <form action="{{ route('register.post') }}" method="POST">
      @csrf
      <div class="mb-2">
        <label>Nama Lengkap</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-2">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-2">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-2">
        <label>Alamat</label>
        <input type="text" name="alamat" class="form-control" required>
      </div>
      <div class="mb-2">
        <label>No HP</label>
        <input type="text" name="no_hp" class="form-control" required>
      </div>
      <div class="mb-2">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-2">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
      </div>
      <div class="mb-2">
        <label>Role</label>
        <select name="role" class="form-select" required>
          <option value="pelanggan">Pelanggan</option>
          <option value="pemilik">Pemilik</option>
          <option value="apoteker">Apoteker</option>
          <option value="karyawan">Karyawan</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Daftar</button>
      <p class="mt-2">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
    </form>
  </div>
</div>
@endsection
