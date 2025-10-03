@extends('layouts.app')
@section('title', 'Registrasi')
@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #00d4ff 0%, #00a8cc 100%);">
  <div class="col-11 col-md-8 col-lg-6 col-xl-5">
    <div class="card border-0 shadow-lg" style="border-radius: 40px; overflow: hidden;">
      <!-- Header -->
      <div class="card-header text-center py-4" style="background: linear-gradient(to bottom, #ffffff 0%, #fff8e6 100%); border-bottom: 4px solid #ffd89b;">
        <h2 class="fw-bold mb-0" style="font-size: 2rem; letter-spacing: 2px;">REGISTRASI</h2>
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

        <form action="{{ route('register.post') }}" method="POST">
          @csrf
          
          <!-- Nama Lengkap -->
          <div class="mb-3">
            <label class="fw-bold mb-2" style="font-size: 1.1rem;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control border-0 shadow-sm" 
                   value="{{ old('name') }}"
                   style="background: linear-gradient(135deg, #ffb347 0%, #ffcc80 100%); 
                          border-radius: 25px; 
                          padding: 12px 20px; 
                          font-size: 1rem;" required>
          </div>

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
          <div class="mb-3">
            <label class="fw-bold mb-2" style="font-size: 1.1rem;">Password</label>
            <input type="password" name="password" class="form-control border-0 shadow-sm" 
                   style="background: linear-gradient(135deg, #ffb347 0%, #ffcc80 100%); 
                          border-radius: 25px; 
                          padding: 12px 20px; 
                          font-size: 1rem;" required>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label class="fw-bold mb-2" style="font-size: 1.1rem;">Email</label>
            <input type="email" name="email" class="form-control border-0 shadow-sm" 
                   value="{{ old('email') }}"
                   style="background: linear-gradient(135deg, #ffb347 0%, #ffcc80 100%); 
                          border-radius: 25px; 
                          padding: 12px 20px; 
                          font-size: 1rem;" required>
          </div>

          <!-- Alamat -->
          <div class="mb-3">
            <label class="fw-bold mb-2" style="font-size: 1.1rem;">Alamat</label>
            <input type="text" name="alamat" class="form-control border-0 shadow-sm" 
                   value="{{ old('alamat') }}"
                   style="background: linear-gradient(135deg, #ffb347 0%, #ffcc80 100%); 
                          border-radius: 25px; 
                          padding: 12px 20px; 
                          font-size: 1rem;" required>
          </div>

          <!-- No.Hp -->
          <div class="mb-4">
            <label class="fw-bold mb-2" style="font-size: 1.1rem;">No.Hp</label>
            <input type="text" name="no_hp" class="form-control border-0 shadow-sm" 
                   value="{{ old('no_hp') }}"
                   style="background: linear-gradient(135deg, #ffb347 0%, #ffcc80 100%); 
                          border-radius: 25px; 
                          padding: 12px 20px; 
                          font-size: 1rem;" required>
          </div>

          <!-- Login Link -->
          <p class="text-center fw-semibold mb-3" style="font-size: 1rem;">
            Sudah Memiliki Akun? <a href="{{ route('login') }}" class="text-primary text-decoration-none">Masuk</a>
          </p>

          <!-- Submit Button -->
          <div class="text-center">
            <button type="submit" class="btn border-0 shadow fw-bold px-5 py-2" 
                    style="background: linear-gradient(135deg, #ff9933 0%, #ff8800 100%); 
                           color: white; 
                           border-radius: 15px; 
                           font-size: 1.1rem;
                           letter-spacing: 1px;">
              DAFTAR
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