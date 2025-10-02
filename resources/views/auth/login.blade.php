@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <h3>Login</h3>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    <form action="{{ route('login.post') }}" method="POST">
      @csrf
      <div class="mb-2">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-2">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Login</button>
      <p class="mt-2">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
    </form>
  </div>
</div>
@endsection
