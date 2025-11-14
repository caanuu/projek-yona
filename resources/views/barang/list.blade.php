@extends('layout')

@section('title', 'Laporan Stok Barang')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Laporan Stok Barang</h2>

        {{-- Tombol "Tambah" dihapus, diganti tombol "Kembali" --}}
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali ke Daftar Barang</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama</th>
                    <th>Harga Beli Terakhir</th>
                    <th>Stok Baik</th>
                    <th>Stok Rusak</th>
                    {{-- Kolom Aksi dihapus --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $index => $barang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $barang->kode_barang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>
                            @php
                                // Ambil detail transaksi MASUK terakhir untuk harga beli
                                $lastDetailMasuk = $barang
                                    ->details()
                                    ->whereNotNull('transaksi_masuk_id')
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                            @endphp
                            {{ $lastDetailMasuk ? 'Rp ' . number_format($lastDetailMasuk->harga_beli, 0, ',', '.') : '-' }}
                        </td>
                        <td>{{ $barang->stok_baik }}</td>
                        <td>{{ $barang->stok_rusak }}</td>
                        {{-- Kolom Aksi dihapus --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
