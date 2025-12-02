<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with('user')->latest()->paginate(10);
        return view('pemilik.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('pemilik.karyawan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:karyawan,apoteker',
            'nama_karyawan' => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'no_telp_karyawan' => 'nullable|string|max:20',
            'alamat_karyawan' => 'nullable|string',
            'tanggal_bergabung' => 'nullable|date',
            'gaji' => 'nullable|numeric|min:0',
        ]);

        // Buat user
        $user = User::create([
            'name' => $validated['nama_karyawan'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'alamat' => $validated['alamat_karyawan'] ?? null,
            'no_hp' => $validated['no_telp_karyawan'] ?? null,
        ]);

        // Buat karyawan
        Karyawan::create([
            'id_user' => $user->id,
            'nama_karyawan' => $validated['nama_karyawan'],
            'jabatan' => $validated['jabatan'],
            'no_telp_karyawan' => $validated['no_telp_karyawan'] ?? null,
            'alamat_karyawan' => $validated['alamat_karyawan'] ?? null,
            'tanggal_bergabung' => $validated['tanggal_bergabung'] ?? now(),
            'gaji' => $validated['gaji'] ?? null,
        ]);

        return redirect()->route('pemilik.karyawan.index')
            ->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function show(Karyawan $karyawan)
    {
        $karyawan->load(['user', 'pesanans']);
        return view('pemilik.karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        $karyawan->load('user');
        return view('pemilik.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $karyawan->id_user,
            'email' => 'required|email|unique:users,email,' . $karyawan->id_user,
            'role' => 'required|in:karyawan,apoteker',
            'nama_karyawan' => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'no_telp_karyawan' => 'nullable|string|max:20',
            'alamat_karyawan' => 'nullable|string',
            'tanggal_bergabung' => 'nullable|date',
            'gaji' => 'nullable|numeric|min:0',
        ]);

        // Update user
        $karyawan->user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // Update karyawan
        $karyawan->update([
            'nama_karyawan' => $validated['nama_karyawan'],
            'jabatan' => $validated['jabatan'],
            'no_telp_karyawan' => $validated['no_telp_karyawan'] ?? null,
            'alamat_karyawan' => $validated['alamat_karyawan'] ?? null,
            'tanggal_bergabung' => $validated['tanggal_bergabung'],
            'gaji' => $validated['gaji'] ?? null,
        ]);

        return redirect()->route('pemilik.karyawan.index')
            ->with('success', 'Karyawan berhasil diupdate');
    }

    public function destroy(Karyawan $karyawan)
    {
        // Hapus user akan otomatis hapus karyawan (cascade)
        $karyawan->user->delete();

        return redirect()->route('pemilik.karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus');
    }
}
