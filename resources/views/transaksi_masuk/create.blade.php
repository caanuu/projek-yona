@extends('layout')

@section('title', 'Tambah Transaksi Masuk')

@section('content')
    <h2 class="mb-4">Tambah Transaksi Masuk</h2>
    <form action="{{ route('transaksi-masuk.store') }}" method="POST" class="card p-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Kode Transaksi</label>
            <input type="text" name="kode_transaksi" class="form-control" required>
        </div>

        {{-- Dropdown Supplier --}}
        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <select name="supplier_id" class="form-select" required>
                <option value="">-- Pilih Supplier --</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                @endforeach
            </select>
        </div>

        {{-- Pegawai Otomatis --}}
        <div class="mb-3">
            <label class="form-label">Pegawai Penerima</label>
            <input type="text" class="form-control bg-light"
                value="{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})" readonly>
            <small class="text-muted">Otomatis terisi sesuai user yang login.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan Transaksi</label>
            <input type="text" name="keterangan_masuk" class="form-control">
        </div>

        <hr class="my-3">
        <h5>Detail Barang</h5>

        <div id="detail-barang-wrapper">
            <div class="row mb-3 align-items-end detail-barang-item">
                <div class="col-md-4">
                    <label class="form-label">Barang</label>
                    <select name="barang_id[]" class="form-select barang-select" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah[]" class="form-control" min="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" name="harga_beli[]" class="form-control" min="0" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove-detail">Hapus</button>
                </div>
            </div>
        </div>

        <button type="button" id="btn-add-detail" class="btn btn-secondary mb-3">+ Tambah Barang</button>
        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('detail-barang-wrapper');
            const btnAdd = document.getElementById('btn-add-detail');
            const template = wrapper.querySelector('.detail-barang-item').cloneNode(true);

            btnAdd.addEventListener('click', function() {
                const newItem = template.cloneNode(true);
                newItem.querySelectorAll('input').forEach(i => i.value = '');
                wrapper.appendChild(newItem);
            });

            wrapper.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-detail') && wrapper.children.length > 1) {
                    e.target.closest('.detail-barang-item').remove();
                }
            });
        });
    </script>
@endsection
