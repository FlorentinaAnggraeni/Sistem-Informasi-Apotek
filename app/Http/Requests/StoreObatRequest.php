<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreObatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_obat' => 'required|string|max:100',
            'jenis_obat' => 'required|string|max:50',
            'deskripsi_obat' => 'nullable|string',
            'harga_obat' => 'required|numeric|min:0',
            'stok_obat' => 'required|integer|min:0',
            'id_kategori' => 'nullable|exists:kategoris,id_kategori',
            'id_supplier' => 'nullable|exists:suppliers,id_supplier',
            'gambar_obat' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tanggal_kadaluarsa' => 'nullable|date|after:today',
            'no_batch' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_obat.required' => 'Nama obat wajib diisi',
            'nama_obat.max' => 'Nama obat maksimal 100 karakter',
            'jenis_obat.required' => 'Jenis obat wajib diisi',
            'harga_obat.required' => 'Harga obat wajib diisi',
            'harga_obat.numeric' => 'Harga obat harus berupa angka',
            'harga_obat.min' => 'Harga obat tidak boleh negatif',
            'stok_obat.required' => 'Stok obat wajib diisi',
            'stok_obat.integer' => 'Stok obat harus berupa angka',
            'stok_obat.min' => 'Stok obat tidak boleh negatif',
            'gambar_obat.image' => 'File harus berupa gambar',
            'gambar_obat.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
            'gambar_obat.max' => 'Ukuran gambar maksimal 2MB',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus setelah hari ini',
        ];
    }
}
