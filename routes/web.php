<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;   // <-- pindahkan use ke luar closure

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// ===== AUTH =====
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== DASHBOARD =====
Route::get('/dashboard', function () {
    if (!session()->has('user')) {
        return redirect()->route('login');
    }

    $user = session('user');
    return view('dashboard', compact('user'));
})->name('dashboard');
