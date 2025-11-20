@extends('layout')

@section('title', 'Edit Barang')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">Edit Barang</h2>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Whoops!</strong> Terjadi kesalahan input.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('barang.update', $barang->id) }}" method="POST"
                class="card border-0 shadow-sm p-4 mb-4">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control bg-light"
                            value="{{ old('kode_barang', $barang->kode_barang) }}" readonly required>
                        <small class="text-muted">Kode barang tidak dapat diubah.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control"
                            value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                    </div>
                </div>

                {{-- PERBAIKAN: Menggunakan Dropdown Kategori --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kategori Barang</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Harga Jual (Master)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga" class="form-control"
                            value="{{ old('harga', $barang->harga) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $barang->keterangan) }}</textarea>
                </div>

                <div class="alert alert-info d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                    <div>
                        <strong>Stok Saat Ini:</strong><br>
                        Baik: <span class="badge bg-success">{{ $barang->stok_baik }}</span> |
                        Rusak: <span class="badge bg-danger">{{ $barang->stok_rusak }}</span>
                        <div class="mt-1 small">Gunakan menu <b>Mutasi Stok</b> untuk mengubah jumlah stok.</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold py-3">
                    Riwayat Mutasi Terakhir
                </div>
                <div class="card-body p-0">
                    @if ($mutasiKondisis->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach ($mutasiKondisis->take(5) as $mutasi)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span
                                            class="badge {{ $mutasi->to_status == 'rusak' ? 'bg-danger' : 'bg-success' }}">
                                            {{ ucfirst($mutasi->to_status) }}
                                        </span>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($mutasi->tanggal)->format('d M Y') }}</small>
                                    </div>
                                    <div class="mt-1 fw-bold">{{ $mutasi->jumlah }} Unit</div>
                                    <small class="text-muted fst-italic">{{ Str::limit($mutasi->keterangan, 30) }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-4 text-center text-muted">
                            Belum ada riwayat mutasi.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
