@extends('layout')

@section('title', 'Daftar Barang')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Daftar Barang</h2>

        {{-- Tombol "Tambah" untuk Admin, Gudang, dan Kasir --}}
        @if(Auth::user()->isAdmin() || Auth::user()->isGudang() || Auth::user()->isKasir())
            <a href="{{ route('barang.create') }}" class="btn btn-success">+ Tambah Barang</a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th class="text-center">Stok Baik</th>
                    <th class="text-center">Stok Rusak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangs as $index => $barang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $barang->kode_barang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->jenis_barang }}</td>
                        <td>{{ $barang->keterangan }}</td>
                        <td class="text-center">{{ $barang->stok_baik }}</td>
                        <td class="text-center text-danger">{{ $barang->stok_rusak }}</td>
                        <td>
                            {{-- Tombol "Edit" untuk Admin, Gudang, dan Kasir --}}
                            @if(Auth::user()->isAdmin() || Auth::user()->isGudang() || Auth::user()->isKasir())
                                <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            @endif

                            {{-- Tombol "Hapus" untuk Admin, Gudang, dan Kasir --}}
                            @if(Auth::user()->isAdmin() || Auth::user()->isGudang() || Auth::user()->isKasir())
                                <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus barang?')">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
