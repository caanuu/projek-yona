@extends('layout')

@section('title', 'Tambah Barang')

@section('content')
    <h2 class="mb-4">Tambah Barang Baru</h2>
    <form action="{{ route('barang.store') }}" method="POST" class="card p-4">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kode Barang</label>
            <input type="text" name="kode_barang" class="form-control" value="{{ old('kode_barang') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required>
        </div>

        {{-- Dropdown Kategori --}}
        <div class="mb-3">
            <label class="form-label">Kategori Barang</label>
            <select name="kategori_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Harga Jual (Master)</label>
                    <input type="number" name="harga" class="form-control" value="{{ old('harga', 0) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label text-primary fw-bold">Stok Awal (Saldo Awal)</label>
                    <input type="number" name="stok_awal" class="form-control border-primary"
                        value="{{ old('stok_awal', 0) }}" min="0">
                    <small class="text-muted">Isi jika barang sudah ada di gudang sebelum sistem digunakan.</small>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
