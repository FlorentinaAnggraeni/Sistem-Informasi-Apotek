<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePelangganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nama_pelanggan' => 'required|string|max:100',
            'alamat_pelanggan' => 'required|string',
            'no_telp_pelanggan' => 'required|string|max:20',
            'tanggal_lahir' => 'nullable|date|before:today',
            'jenis_kelamin' => 'nullable|in:L,P',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi',
            'alamat_pelanggan.required' => 'Alamat pelanggan wajib diisi',
            'no_telp_pelanggan.required' => 'No telepon wajib diisi',
            'tanggal_lahir.before' => 'Tanggal lahir tidak valid',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
        ];
    }
}
