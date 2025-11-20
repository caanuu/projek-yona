@extends('layout')
@section('title', 'Daftar Barang')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Daftar Barang</h2>

        {{-- Tombol Tambah: HANYA ADMIN & GUDANG (Kasir tidak boleh tambah barang master) --}}
        @if (Auth::user()->isAdmin() || Auth::user()->isGudang())
            <a href="{{ route('barang.create') }}" class="btn btn-success">+ Tambah Barang</a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th>Stok Baik</th>
                    <th>Stok Rusak</th>

                    {{-- Kolom Aksi hanya untuk Admin & Gudang --}}
                    @if (Auth::user()->isAdmin() || Auth::user()->isGudang())
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $index => $barang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $barang->kode_barang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->kategori ? $barang->kategori->nama_kategori : '-' }}</td>
                        <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $barang->stok_baik }}</td>
                        <td class="text-center text-danger">{{ $barang->stok_rusak }}</td>

                        {{-- Logika Tombol Aksi --}}
                        @if (Auth::user()->isAdmin() || Auth::user()->isGudang())
                            <td>
                                {{-- Edit: Admin & Gudang Boleh --}}
                                <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-primary">Edit</a>

                                {{-- Hapus: HANYA ADMIN (Gudang tidak boleh hapus permanen) --}}
                                @if (Auth::user()->isAdmin())
                                    <form action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus data ini permanen?')">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
