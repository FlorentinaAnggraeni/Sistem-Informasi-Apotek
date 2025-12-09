@extends('layouts.app')
@section('title', 'Registrasi')
@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #00bcd4 0%, #00838f 100%);">
  <div class="col-11 col-md-8 col-lg-6 col-xl-5">
    <div class="card border-0 shadow-lg" style="border-radius: 40px; overflow: hidden;">
      <!-- Header -->
      <div class="card-header text-center py-4" style="background: linear-gradient(to bottom, #0097a7 0%, #006064 100%); border-bottom: 4px solid #00bcd4;">
        <h2 class="fw-bold mb-0 text-white" style="font-size: 2rem; letter-spacing: 2px;">REGISTRASI</h2>
      </div>
      
      <!-- Body -->
      <div class="card-body p-4 p-md-5" style="background: linear-gradient(to bottom, #e0f7fa 0%, #b2ebf2 100%);">
        
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
            <label class="fw-bold mb-2" style="font-size: 1.1rem; color: #006064;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control border-0 shadow-sm" 
                   value="{{ old('name') }}"
                   style="background: #ffffff; 
                          border: 2px solid #00bcd4;
                          border-radius: 15px; 
                          padding: 12px 20px; 
                          font-size: 1rem; 
                          color: #006064;" required>
          </div>

          <!-- Username -->
          <div class="mb-3">
            <label class="fw-bold mb-2" style="font-size: 1.1rem; color: #006064;">Username</label>
            <input type="text" name="username" class="form-control border-0 shadow-sm" 
                   value="{{ old('username') }}"
                   style="background: #ffffff; 
                          border: 2px solid #00bcd4;
                          border-radius: 15px; 
                          padding: 12px 20px; 
                          font-size: 1rem; 
                          color: #006064;" required>
          </div>

          <!-- Password -->
          <div class="mb-3">
            <label class="fw-bold mb-2" style="font-size: 1.1rem; color: #006064;">Password</label>
            <input type="password" name="password" class="form-control border-0 shadow-sm" 
                   style="background: #ffffff; 
                          border: 2px solid #00bcd4;
                          border-radius: 15px; 
                          padding: 12px 20px; 
                          font-size: 1rem; 
                          color: #006064;" required>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label class="fw-bold mb-2" style="font-size: 1.1rem; color: #006064;">Email</label>
            <input type="email" name="email" class="form-control border-0 shadow-sm" 
                   value="{{ old('email') }}"
                   style="background: #ffffff; 
                          border: 2px solid #00bcd4;
                          border-radius: 15px; 
                          padding: 12px 20px; 
                          font-size: 1rem; 
                          color: #006064;" required>
          </div>

          <!-- Alamat -->
          <div class="mb-3">
            <label class="fw-bold mb-2" style="font-size: 1.1rem; color: #006064;">Alamat</label>
            <input type="text" name="alamat" class="form-control border-0 shadow-sm" 
                   value="{{ old('alamat') }}"
                   style="background: #ffffff; 
                          border: 2px solid #00bcd4;
                          border-radius: 15px; 
                          padding: 12px 20px; 
                          font-size: 1rem; 
                          color: #006064;" required>
          </div>

          <!-- No.Hp -->
          <div class="mb-4">
            <label class="fw-bold mb-2" style="font-size: 1.1rem; color: #006064;">No.Hp</label>
            <input type="text" name="no_hp" class="form-control border-0 shadow-sm" 
                   value="{{ old('no_hp') }}"
                   style="background: #ffffff; 
                          border: 2px solid #00bcd4;
                          border-radius: 15px; 
                          padding: 12px 20px; 
                          font-size: 1rem; 
                          color: #006064;" required>
          </div>

          <!-- Login Link -->
          <p class="text-center fw-semibold mb-3" style="font-size: 1rem; color: #006064;">
            Sudah Memiliki Akun? <a href="{{ route('login') }}" class="text-decoration-none" style="color: #0097a7;">Masuk</a>
          </p>

          <!-- Submit Button -->
          <div class="text-center">
            <button type="submit" class="btn border-0 shadow fw-bold px-5 py-2" 
                    style="background: linear-gradient(135deg, #0097a7 0%, #006064 100%); 
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
    box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.3) !important;
    outline: none;
  }
  
  .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 151, 167, 0.4) !important;
    transition: all 0.3s ease;
  }
</style>
@endsection