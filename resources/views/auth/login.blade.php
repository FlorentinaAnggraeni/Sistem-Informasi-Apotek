@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #00bcd4 0%, #00838f 100%);">
  <div class="col-11 col-md-6 col-lg-5 col-xl-4">
    <div class="card border-0 shadow-lg" style="border-radius: 40px; overflow: hidden;">
      <!-- Header -->
      <div class="card-header text-center py-4" style="background: linear-gradient(to bottom, #0097a7 0%, #006064 100%); border-bottom: 4px solid #00bcd4;">
        <h2 class="fw-bold mb-0 text-white" style="font-size: 2rem; letter-spacing: 2px;">LOGIN</h2>
      </div>
      
      <!-- Body -->
      <div class="card-body p-4 p-md-5" style="background: linear-gradient(to bottom, #e0f7fa 0%, #b2ebf2 100%);">

        <form action="{{ route('login.post') }}" method="POST">
          @csrf
          
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
          <div class="mb-4">
            <label class="fw-bold mb-2" style="font-size: 1.1rem; color: #006064;">Password</label>
            <input type="password" name="password" class="form-control border-0 shadow-sm" 
                   style="background: #ffffff; 
                          border: 2px solid #00bcd4;
                          border-radius: 15px; 
                          padding: 12px 20px; 
                          font-size: 1rem; 
                          color: #006064;" required>
          </div>

          <!-- Remember Me -->
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label fw-semibold" for="remember" style="color: #006064;">
              Ingat Saya
            </label>
          </div>

          <!-- Register Link -->
          <p class="text-center fw-semibold mb-3" style="font-size: 1rem; color: #006064;">
            Belum Punya Akun? <a href="{{ route('register') }}" class="text-decoration-none" style="color: #0097a7;">Daftar</a>
          </p>

          <!-- Submit Button -->
          <div class="text-center">
            <button type="submit" class="btn border-0 shadow fw-bold px-5 py-2" 
                    style="background: linear-gradient(135deg, #0097a7 0%, #006064 100%); 
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
    box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.3) !important;
    outline: none;
  }
  
  .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 151, 167, 0.4) !important;
    transition: all 0.3s ease;
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  @if($errors->any())
    var errorMessages = '{!! implode("<br>", $errors->all()) !!}';
    Swal.fire({
      icon: 'error',
      title: 'Login Gagal!',
      html: errorMessages,
      confirmButtonText: 'Coba Lagi',
      confirmButtonColor: '#00bcd4',
      allowOutsideClick: false,
      didOpen: function() {
        document.querySelector('input[name="username"]').focus();
      }
    });
  @endif

  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Sukses!',
      text: '{{ session("success") }}',
      confirmButtonText: 'OK',
      confirmButtonColor: '#00bcd4'
    });
  @endif
</script>
@endsection