@extends('layout')

@section('title', 'Tambah Barang')

@section('content')
    <h2 class="mb-4">Tambah Barang Baru</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops! Terjadi kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('barang.store') }}" method="POST" class="card p-4">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kode Barang</label>
            {{-- Tambahkan value="old(...)" --}}
            <input type="text" name="kode_barang" class="form-control" value="{{ old('kode_barang') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Barang</label>
            {{-- Tambahkan value="old(...)" --}}
            <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jenis Barang</label>
            {{-- Tambahkan value="old(...)" --}}
            <input type="text" name="jenis_barang" class="form-control" value="{{ old('jenis_barang') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            {{-- Tambahkan value="old(...)" di dalam textarea --}}
            <textarea name="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
