<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alamat_pengiriman' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:1000',
            'metode_pembayaran' => 'required|in:transfer,e-wallet,qris',
        ];
    }

    public function messages(): array
    {
        return [
            'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi',
            'alamat_pengiriman.max' => 'Alamat pengiriman maksimal 500 karakter',
            'catatan.max' => 'Catatan maksimal 1000 karakter',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid',
        ];
    }
}
