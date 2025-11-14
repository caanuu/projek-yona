<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Menampilkan daftar barang
     */
    public function index()
    {
        $barangs = Barang::with('mutasiKondisis')->get();
        return view('barang.index', compact('barangs'));
    }

    public function list()
    {
        $barangs = Barang::with('mutasiKondisis')->get();
        return view('barang.list', compact('barangs'));
    }

    /**
     * Menampilkan form tambah barang
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Simpan barang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required',
            'jenis_barang' => 'required',
        ]);

        Barang::create([
            'kode_barang'   => $request->kode_barang,
            'nama_barang'   => $request->nama_barang,
            'jenis_barang'  => $request->jenis_barang,
            'keterangan'    => $request->keterangan,
            'harga_default' => null, // awalnya null
            'stok_baik'     => 0,
            'stok_rusak'    => 0,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Menampilkan detail barang
     */
    public function show(Barang $barang)
    {
        // Fungsi ini sepertinya tidak terpakai, tapi kita biarkan
        return view('barang.show', compact('barang'));
    }

    /**
     * Menampilkan form edit barang
     */
    public function edit(Barang $barang)
    {
        $mutasiKondisis = $barang->mutasiKondisis()->latest()->get();
        return view('barang.edit', compact('barang', 'mutasiKondisis'));
    }

    /**
     * === FUNGSI UPDATE DIPERBARUI ===
     * Logika mutasi dipindahkan ke MutasiKondisiController
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required',
            'jenis_barang' => 'required',
            'keterangan' => 'nullable|string', // Sesuaikan validasi
        ]);

        // Update master barang
        $barang->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'jenis_barang' => $request->jenis_barang,
            'keterangan' => $request->keterangan,
        ]);

        // Logika mutasi yang membingungkan sudah dihapus dari sini.
        // Mutasi stok sekarang ditangani oleh MutasiKondisiController.

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }


    /**
     * Hapus barang
     * === DIPERBARUI DI SINI ===
     */
    public function destroy(Barang $barang)
    {
        // Menggunakan forceDelete() untuk menghapus permanen dari database
        $barang->forceDelete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus permanen');
    }

    public function rusak()
    {
        $barangs = Barang::with('mutasiKondisis')
                        ->where('stok_rusak', '>', 0)
                        ->get();

        return view('barang.rusak', compact('barangs'));
    }

    public function getStok($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(['stok_baik' => 0]);
        }

        return response()->json([
            'stok_baik' => $barang->stok_baik,
            'stok_rusak' => $barang->stok_rusak ?? 0,
        ]);
    }
}
