<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateObatRequest extends FormRequest
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
            'tanggal_kadaluarsa' => 'nullable|date',
            'no_batch' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_obat.required' => 'Nama obat wajib diisi',
            'jenis_obat.required' => 'Jenis obat wajib diisi',
            'harga_obat.required' => 'Harga obat wajib diisi',
            'harga_obat.numeric' => 'Harga obat harus berupa angka',
            'stok_obat.required' => 'Stok obat wajib diisi',
            'gambar_obat.image' => 'File harus berupa gambar',
            'gambar_obat.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
