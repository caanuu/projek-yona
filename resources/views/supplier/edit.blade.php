@extends('layout')
@section('title', 'Edit Supplier')

@section('content')
    <h2 class="mb-4">Edit Supplier</h2>
    <form action="{{ route('supplier.update', $supplier->id) }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama Supplier</label>
            <input type="text" name="nama_supplier" class="form-control" value="{{ $supplier->nama_supplier }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="telp" class="form-control" value="{{ $supplier->telp }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3">{{ $supplier->alamat }}</textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
