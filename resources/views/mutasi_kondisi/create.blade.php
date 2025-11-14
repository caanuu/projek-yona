@extends('layout')

@section('title', 'Mutasi Kondisi Stok')

@section('content')
    <h2 class="mb-4">Mutasi Kondisi Stok</h2>

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

    <form action="{{ route('mutasi-kondisi.store') }}" method="POST" class="card p-4">
        @csrf

        <div class="mb-3">
            <label for="barang_id" class="form-label">Barang</label>
            <select name="barang_id" id="barang_id" class="form-select" required>
                <option value="">-- Pilih Barang --</option>
                @foreach ($barangs as $barang)
                    <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                        {{ $barang->nama_barang }} (Baik: {{ $barang->stok_baik }}, Rusak: {{ $barang->stok_rusak }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Mutasi</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control"
                value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="from_status" class="form-label">Dari Status</Slabel>
                        <select name="from_status" id="from_status" class="form-select" required>
                            <option value="">-- Pilih Status Awal --</option>
                            <option value="baik" {{ old('from_status') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ old('from_status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="to_status" class="form-label">Ke Status</label>
                    <select name="to_status" id="to_status" class="form-select" required>
                        <option value="">-- Pilih Status Tujuan --</option>
                        <option value="baik" {{ old('to_status') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak" {{ old('to_status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah') }}"
                min="1" required>
            <small class="text-muted">Pastikan jumlah tidak melebihi stok yang ada pada status awal.</small>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan (Alasan Mutasi)</label>
            {{-- DIPERBARUI DI SINI --}}
            <textarea name="keterangan" id="keterangan" class="form-control" required>{{ old('keterangan') }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan Mutasi</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
