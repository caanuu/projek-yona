<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\TransaksiKeluar;
use App\Models\DetailBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\TransaksiKeluarExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class TransaksiKeluarController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'month');
        $label = '';
        $dates = [];

        if ($filter == 'week') {
            $start = $request->get('start') ? Carbon::parse($request->get('start')) : now()->startOfWeek();
            $end = $start->copy()->endOfWeek();
            $label = 'Minggu ' . $start->format('d M') . ' - ' . $end->format('d M Y');
            $dates = CarbonPeriod::create($start, $end)->toArray();
        } elseif ($filter == 'month') {
            $month = $request->get('month', now()->month);
            $year = $request->get('year', now()->year);
            $start = Carbon::create($year, $month, 1);
            $end = $start->copy()->endOfMonth();
            $label = $start->translatedFormat('F Y');
            $dates = CarbonPeriod::create($start, $end)->toArray();
        } elseif ($filter == 'year') {
            $year = $request->get('year', now()->year);
            $label = 'Tahun ' . $year;
            $start = Carbon::create($year, 1, 1);
            $end = Carbon::create($year, 12, 31);
            $dates = CarbonPeriod::create($start, $end)->toArray();
        }

        $dateStrings = array_map(fn($d) => $d->toDateString(), $dates);

        $transaksiKeluars = TransaksiKeluar::with(['details.barang', 'user'])
            ->when(!empty($dateStrings), function ($query) use ($dateStrings) {
                $query->whereDate('created_at', '>=', reset($dateStrings))
                    ->whereDate('created_at', '<=', end($dateStrings));
            })
            ->latest()
            ->get();

        $jumlahbarang = DetailBarang::with('barang')
            ->select(
                'barang_id',
                DB::raw('SUM(jumlah) as jumlah'),
                DB::raw('SUM(harga_jual * jumlah) as pendapatan')
            )
            ->whereNotNull('transaksi_keluar_id')
            ->when(!empty($dateStrings), function ($query) use ($dateStrings) {
                $query->whereHas('transaksiKeluar', function ($q) use ($dateStrings) {
                    $q->whereDate('created_at', '>=', reset($dateStrings))
                        ->whereDate('created_at', '<=', end($dateStrings));
                });
            })
            ->groupBy('barang_id')
            ->orderBy('jumlah', 'desc')
            ->get();

        return view('transaksi_keluar.index', compact('transaksiKeluars', 'jumlahbarang', 'filter', 'label'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('transaksi_keluar.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:transaksi_keluars,kode_transaksi',
            'customer' => 'required',
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah.*' => 'required|integer|min:1',
            'harga_jual.*' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {
            $transaksi = TransaksiKeluar::create([
                'kode_transaksi' => $request->kode_transaksi,
                'customer' => $request->customer,
                'user_id' => Auth::id(),
                'keterangan_keluar' => $request->keterangan_keluar,
                'qty' => array_sum($request->jumlah),
            ]);

            foreach ($request->barang_id as $index => $barang_id) {
                $jumlah = $request->jumlah[$index];
                $hargaJual = $request->harga_jual[$index];
                $barang = Barang::find($barang_id);

                if ($barang->stok_baik < $jumlah) {
                    throw new \Exception("Stok barang {$barang->nama_barang} tidak mencukupi.");
                }

                DetailBarang::create([
                    'transaksi_keluar_id' => $transaksi->id,
                    'barang_id' => $barang_id,
                    'status' => 'terjual',
                    'jumlah' => $jumlah,
                    'harga_jual' => $hargaJual,
                ]);

                $barang->stok_baik -= $jumlah;
                $barang->save();
            }
        });

        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi berhasil dicatat');
    }

    // --- FITUR EDIT ---
    public function edit($id)
    {
        $transaksi = TransaksiKeluar::with('details.barang')->findOrFail($id);
        $barangs = Barang::all();
        return view('transaksi_keluar.edit', compact('transaksi', 'barangs'));
    }

    // --- FITUR UPDATE (DENGAN STOK ROLLBACK) ---
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer' => 'required',
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah.*' => 'required|integer|min:1',
            'harga_jual.*' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request, $id) {
            $transaksi = TransaksiKeluar::with('details')->findOrFail($id);

            // 1. KEMBALIKAN STOK LAMA
            foreach ($transaksi->details as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->stok_baik += $detail->jumlah;
                    $barang->save();
                }
                $detail->delete();
            }

            // 2. UPDATE TRANSAKSI UTAMA
            $transaksi->update([
                'customer' => $request->customer,
                'keterangan_keluar' => $request->keterangan_keluar,
                'qty' => array_sum($request->jumlah),
            ]);

            // 3. MASUKKAN DETAIL BARU & KURANGI STOK
            foreach ($request->barang_id as $index => $barang_id) {
                $jumlah = $request->jumlah[$index];
                $hargaJual = $request->harga_jual[$index];
                $barang = Barang::find($barang_id);

                if ($barang->stok_baik < $jumlah) {
                    throw new \Exception("Stok barang {$barang->nama_barang} tidak cukup untuk update ini.");
                }

                DetailBarang::create([
                    'transaksi_keluar_id' => $transaksi->id,
                    'barang_id' => $barang_id,
                    'status' => 'terjual',
                    'jumlah' => $jumlah,
                    'harga_jual' => $hargaJual,
                ]);

                $barang->stok_baik -= $jumlah;
                $barang->save();
            }
        });

        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    // --- FITUR HAPUS (DENGAN PENGEMBALIAN STOK) ---
    public function destroy($id)
    {
        $transaksi = TransaksiKeluar::with('details')->findOrFail($id);

        DB::transaction(function () use ($transaksi) {
            // Kembalikan stok
            foreach ($transaksi->details as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->stok_baik += $detail->jumlah;
                    $barang->save();
                }
            }
            // Hapus data
            $transaksi->details()->delete();
            $transaksi->delete();
        });

        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi dihapus, stok barang telah dikembalikan.');
    }

    public function print($id)
    {
        $transaksi = TransaksiKeluar::with(['details.barang', 'user'])->findOrFail($id);
        return view('transaksi_keluar.print', compact('transaksi'));
    }

    public function export(Request $request)
    {
        $filter = $request->filter ?? 'month';
        $query = TransaksiKeluar::with(['details.barang', 'user']);

        if ($filter === 'week') {
            $start = Carbon::parse($request->start ?? now()->startOfWeek());
            $end = $start->copy()->endOfWeek();
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($filter === 'month') {
            $month = $request->month ?? now()->month;
            $year = $request->year ?? now()->year;
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
        } elseif ($filter === 'year') {
            $year = $request->year ?? now()->year;
            $query->whereYear('created_at', $year);
        }

        return Excel::download(new TransaksiKeluarExport($query->get()), 'transaksi_keluar.xlsx');
    }
}
