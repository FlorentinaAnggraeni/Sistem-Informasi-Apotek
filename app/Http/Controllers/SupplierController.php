<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('obats')->latest()->paginate(10);
        return view('apoteker.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('apoteker.supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'alamat_supplier' => 'required|string',
            'no_telp_supplier' => 'required|string|max:20',
            'email_supplier' => 'nullable|email|max:100',
            'kontak_person' => 'nullable|string|max:100',
        ]);

        Supplier::create($validated);

        return redirect()->route('apoteker.supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('obats');
        return view('apoteker.supplier.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('apoteker.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'alamat_supplier' => 'required|string',
            'no_telp_supplier' => 'required|string|max:20',
            'email_supplier' => 'nullable|email|max:100',
            'kontak_person' => 'nullable|string|max:100',
        ]);

        $supplier->update($validated);

        return redirect()->route('apoteker.supplier.index')
            ->with('success', 'Supplier berhasil diupdate');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('apoteker.supplier.index')
            ->with('success', 'Supplier berhasil dihapus');
    }
}
