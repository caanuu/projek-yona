@extends('layout')

@section('title', 'Laporan Barang Rusak')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Laporan Barang Rusak</h2>

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
                    <th>Stok Rusak</th>
                    <th>Keterangan Rusak</th>
                    {{-- Kolom Aksi dihapus --}}
                </tr>
            </thead>
            <tbody>
                {{-- Controller sudah memfilter, jadi $barangs sudah berisi barang rusak saja --}}
                @forelse ($barangs as $index => $barang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $barang->kode_barang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->stok_rusak }}</td>
                        <td>
                            {{-- Menampilkan riwayat mutasi yang menyebabkan barang ini rusak --}}
                            @forelse($barang->mutasiKondisis->where('to_status', 'rusak') as $mutasi)
                                {{-- DIPERBARUI DI SINI --}}
                                <div>
                                    • {{ $mutasi->keterangan ?? 'Tidak ada keterangan' }}
                                    <span class="text-danger">(Jumlah: {{ $mutasi->jumlah }})</span>
                                </div>
                            @empty
                                <span class="text-muted">Tidak ada catatan</span>
                            @endforelse
                        </td>
                        {{-- Kolom Aksi dihapus --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada barang rusak.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
