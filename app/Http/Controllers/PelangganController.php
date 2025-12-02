<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::with('user')->latest()->paginate(10);
        return view('pemilik.pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pemilik.pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nama_pelanggan' => 'required|string|max:100',
            'alamat_pelanggan' => 'required|string',
            'no_telp_pelanggan' => 'required|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
        ]);

        // Buat user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pelanggan',
        ]);

        // Buat pelanggan
        Pelanggan::create([
            'id_user' => $user->id,
            'nama_pelanggan' => $validated['nama_pelanggan'],
            'alamat_pelanggan' => $validated['alamat_pelanggan'],
            'no_telp_pelanggan' => $validated['no_telp_pelanggan'],
            'email_pelanggan' => $validated['email'],
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
        ]);

        return redirect()->route('pemilik.pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['user', 'pesanans']);
        return view('pemilik.pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        $pelanggan->load('user');
        return view('pemilik.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pelanggan->id_user,
            'nama_pelanggan' => 'required|string|max:100',
            'alamat_pelanggan' => 'required|string',
            'no_telp_pelanggan' => 'required|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
        ]);

        // Update user
        $pelanggan->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update pelanggan
        $pelanggan->update([
            'nama_pelanggan' => $validated['nama_pelanggan'],
            'alamat_pelanggan' => $validated['alamat_pelanggan'],
            'no_telp_pelanggan' => $validated['no_telp_pelanggan'],
            'email_pelanggan' => $validated['email'],
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
        ]);

        return redirect()->route('pemilik.pelanggan.index')
            ->with('success', 'Pelanggan berhasil diupdate');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        // Hapus user akan otomatis hapus pelanggan (cascade)
        $pelanggan->user->delete();

        return redirect()->route('pemilik.pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus');
    }
}
