@extends('layouts.app')
@section('content')
<div class="text-center">
  <h2>Halo, {{ $user->name }} 👋</h2>
  <p>Anda login sebagai <strong>{{ ucfirst($user->role) }}</strong></p>
</div>
@endsection
