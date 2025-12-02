@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #00d4ff 0%, #00a8cc 100%);">
  <div class="col-11 col-md-6 col-lg-5 col-xl-4">
    <div class="card border-0 shadow-lg" style="border-radius: 40px; overflow: hidden;">
      <!-- Header -->
      <div class="card-header text-center py-4" style="background: linear-gradient(to bottom, #ffffff 0%, #fff8e6 100%); border-bottom: 4px solid #ffd89b;">
        <h2 class="fw-bold mb-0" style="font-size: 2rem; letter-spacing: 2px;">LOGIN</h2>
      </div>
      
      <!-- Body -->
      <div class="card-body p-4 p-md-5" style="background: linear-gradient(to bottom, #ffeaa7 0%, #fdcb6e 100%);">
        
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
          @csrf
          
          <!-- Username -->
          <div class="mb-3">
            <label class="fw-bold mb-2" style="font-size: 1.1rem;">Username</label>
            <input type="text" name="username" class="form-control border-0 shadow-sm" 
                   value="{{ old('username') }}"
                   style="background: linear-gradient(135deg, #ffb347 0%, #ffcc80 100%); 
                          border-radius: 25px; 
                          padding: 12px 20px; 
                          font-size: 1rem;" required>
          </div>

          <!-- Password -->
          <div class="mb-4">
            <label class="fw-bold mb-2" style="font-size: 1.1rem;">Password</label>
            <input type="password" name="password" class="form-control border-0 shadow-sm" 
                   style="background: linear-gradient(135deg, #ffb347 0%, #ffcc80 100%); 
                          border-radius: 25px; 
                          padding: 12px 20px; 
                          font-size: 1rem;" required>
          </div>

          <!-- Remember Me -->
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label fw-semibold" for="remember">
              Ingat Saya
            </label>
          </div>

          <!-- Register Link -->
          <p class="text-center fw-semibold mb-3" style="font-size: 1rem;">
            Belum Punya Akun? <a href="{{ route('register') }}" class="text-primary text-decoration-none">Daftar</a>
          </p>

          <!-- Submit Button -->
          <div class="text-center">
            <button type="submit" class="btn border-0 shadow fw-bold px-5 py-2" 
                    style="background: linear-gradient(135deg, #ff9933 0%, #ff8800 100%); 
                           color: white; 
                           border-radius: 15px; 
                           font-size: 1.1rem;
                           letter-spacing: 1px;">
              MASUK
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
  .form-control:focus {
    box-shadow: 0 0 0 3px rgba(255, 179, 71, 0.3) !important;
    outline: none;
  }
  
  .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 136, 0, 0.4) !important;
    transition: all 0.3s ease;
  }
</style>
@endsection