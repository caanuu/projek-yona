@extends('layout')
@section('title', 'Tambah Supplier')

@section('content')
    <h2 class="mb-4">Tambah Supplier Baru</h2>
    <form action="{{ route('supplier.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Supplier</label>
            <input type="text" name="nama_supplier" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="telp" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3"></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
