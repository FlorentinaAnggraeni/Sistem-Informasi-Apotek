<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKaryawanRequest extends FormRequest
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
            'role' => 'required|in:karyawan,apoteker',
            'nama_karyawan' => 'required|string|max:100',
            'jabatan' => 'required|string|max:50',
            'no_telp_karyawan' => 'nullable|string|max:20',
            'alamat_karyawan' => 'nullable|string',
            'tanggal_bergabung' => 'nullable|date',
            'gaji' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
            'nama_karyawan.required' => 'Nama karyawan wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'gaji.numeric' => 'Gaji harus berupa angka',
        ];
    }
}
