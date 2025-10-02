<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apotek</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="{{ route('dashboard') }}">Apotek</a>
      @if(session()->has('user'))
        <span class="text-white me-3">{{ session('user')->role }}</span>
        <a href="{{ route('logout') }}" class="btn btn-outline-light">Logout</a>
      @endif
    </div>
  </nav>
  <div class="container mt-4">
      @yield('content')
  </div>
</body>
</html>
