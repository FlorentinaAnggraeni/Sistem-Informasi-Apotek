<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'username'=>'required|unique:users,username',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed',
            'alamat'=>'required',
            'no_hp'=>'required',
            'role'=>'required|in:pelanggan,pemilik,apoteker,karyawan'
        ]);

        User::create([
            'name'=>$request->name,
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'alamat'=>$request->alamat,
            'no_hp'=>$request->no_hp,
            'role'=>$request->role
        ]);

        return redirect()->route('login')->with('success','Registrasi berhasil!');
    }

    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);

        $user = User::where('username',$request->username)->first();

        if($user && Hash::check($request->password, $user->password)){
            session(['user'=>$user]);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['loginError'=>'Username atau password salah!']);
    }

    // Logout
    public function logout()
    {
        session()->forget('user');
        return redirect()->route('login')->with('success','Berhasil logout.');
    }
}
