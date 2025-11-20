<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('kategori')->latest()->get();
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'nullable|numeric',
            'stok_awal' => 'nullable|integer|min:0', // Validasi tambahan
        ]);

        // Simpan Barang
        $barang = \App\Models\Barang::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'keterangan' => $request->keterangan,
            'harga' => $request->harga ?? 0,
            // Jika ada input stok awal, masukkan ke stok_baik, jika tidak 0
            'stok_baik' => $request->stok_awal ?? 0,
            'stok_rusak' => 0,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        $mutasiKondisis = $barang->mutasiKondisis()->latest()->get();
        return view('barang.edit', compact('barang', 'mutasiKondisis', 'kategoris'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga' => 'nullable|numeric',
        ]);

        $barang->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'keterangan' => $request->keterangan,
            'harga' => $request->harga,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->forceDelete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus permanen');
    }

    public function list()
    {
        $barangs = Barang::with('kategori')->get();
        return view('barang.list', compact('barangs'));
    }

    public function rusak()
    {
        $barangs = Barang::with(['mutasiKondisis', 'kategori'])->where('stok_rusak', '>', 0)->get();
        return view('barang.rusak', compact('barangs'));
    }

    public function getStok($id)
    {
        $barang = Barang::find($id);
        if (!$barang)
            return response()->json(['stok_baik' => 0]);
        return response()->json([
            'stok_baik' => $barang->stok_baik,
            'stok_rusak' => $barang->stok_rusak,
            'harga' => $barang->harga
        ]);
    }
}
