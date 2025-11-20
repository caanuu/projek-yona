@extends('layout')

@section('title', 'Edit Transaksi Keluar')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Edit Transaksi Keluar</h2>
        <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Perhatian:</strong> Mengedit transaksi akan mengembalikan stok lama ke gudang terlebih dahulu, lalu
        mengurangi stok baru sesuai inputan di bawah ini.
    </div>

    <form action="{{ route('transaksi-keluar.update', $transaksi->id) }}" method="POST" class="card p-4 shadow-sm border-0">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Kode Transaksi</label>
                <input type="text" class="form-control bg-light" value="{{ $transaksi->kode_transaksi }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Nama Pelanggan</label>
                <input type="text" name="customer" class="form-control" value="{{ $transaksi->customer }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan / Keterangan</label>
            <input type="text" name="keterangan_keluar" class="form-control" value="{{ $transaksi->keterangan_keluar }}">
        </div>

        <hr class="my-4">
        <h5 class="mb-3 fw-bold text-secondary">Daftar Barang</h5>

        <div id="detail-barang-wrapper">
            @foreach ($transaksi->details as $detail)
                <div class="detail-barang-item card bg-light p-3 mb-2 border-0">
                    <div class="row align-items-end barang-row">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Nama Barang</label>
                            <select name="barang_id[]" class="form-select barang-select" required>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}"
                                        {{ $barang->id == $detail->barang_id ? 'selected' : '' }}>
                                        {{ $barang->nama_barang }} (Stok: {{ $barang->stok_baik }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Qty</label>
                            <input type="number" name="jumlah[]" class="form-control" min="1"
                                value="{{ $detail->jumlah }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Harga Satuan</label>
                            <input type="number" name="harga_jual[]" class="form-control" min="0"
                                value="{{ $detail->harga_jual }}" required>
                        </div>
                        <div class="col-md-2 pt-2">
                            <button type="button" class="btn btn-danger btn-sm btn-remove-detail w-100">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3 d-flex gap-2">
            <button type="button" id="btn-add-detail" class="btn btn-outline-primary">+ Tambah Barang Lain</button>
            <button type="submit" class="btn btn-primary px-4 ms-auto">Simpan Perubahan</button>
        </div>
    </form>

    {{-- Template Hidden --}}
    <div id="template-row" style="display:none;">
        <div class="detail-barang-item card bg-light p-3 mb-2 border-0">
            <div class="row align-items-end barang-row">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Nama Barang</label>
                    <select name="barang_id[]" class="form-select barang-select">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }} (Stok:
                                {{ $barang->stok_baik }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Qty</label>
                    <input type="number" name="jumlah[]" class="form-control" min="1" placeholder="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Harga Satuan</label>
                    <input type="number" name="harga_jual[]" class="form-control" min="0" placeholder="0">
                </div>
                <div class="col-md-2 pt-2">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-detail w-100"><i class="bi bi-trash"></i>
                        Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('detail-barang-wrapper');
            const btnAdd = document.getElementById('btn-add-detail');
            const template = document.getElementById('template-row').firstElementChild;

            btnAdd.addEventListener('click', function() {
                const newItem = template.cloneNode(true);
                wrapper.appendChild(newItem);
            });

            wrapper.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove-detail')) {
                    const rows = wrapper.querySelectorAll('.detail-barang-item');
                    if (rows.length > 1) {
                        e.target.closest('.detail-barang-item').remove();
                    } else {
                        alert("Minimal harus ada 1 barang.");
                    }
                }
            });
        });
    </script>
@endsection
