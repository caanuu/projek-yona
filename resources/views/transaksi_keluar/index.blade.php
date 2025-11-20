@extends('layout')

@section('title', 'Transaksi Keluar')

@section('content')

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('transaksi-keluar.index') }}" method="GET" class="row g-3 align-items-end">
                {{-- Filter Section (Sama seperti sebelumnya) --}}
                <div class="col-md-3">
                    <label for="filter" class="form-label">Filter Berdasarkan</label>
                    <select name="filter" id="filter" class="form-select" onchange="this.form.submit()">
                        <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Mingguan</option>
                        <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Bulanan</option>
                        <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
                @if ($filter == 'month')
                    <div class="col-md-2">
                        <label class="form-label">Bulan</label>
                        <select name="month" class="form-select">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}"
                                    {{ $m == request('month', now()->month) ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="year" class="form-control"
                            value="{{ request('year', now()->year) }}">
                    </div>
                @endif
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <h2>Transaksi Keluar</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('transaksi-keluar.export', request()->query()) }}" class="btn btn-success">📤 Excel</a>
            <a href="{{ route('transaksi-keluar.create') }}" class="btn btn-primary">+ Tambah Transaksi</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Customer</th>
                    <th>Tanggal</th>
                    <th>Barang (Qty)</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksiKeluars as $index => $trx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $trx->kode_transaksi }}</td>
                        <td>{{ $trx->customer }}</td>
                        <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <ul class="mb-0 ps-3">
                                @foreach ($trx->details as $d)
                                    <li>{{ $d->barang->nama_barang }} ({{ $d->jumlah }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            Rp {{ number_format($trx->details->sum(fn($d) => $d->harga_jual * $d->jumlah), 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                {{-- PRINT --}}
                                <a href="{{ route('transaksi-keluar.print', $trx->id) }}"
                                    class="btn btn-sm btn-outline-secondary" target="_blank" title="Cetak">
                                    <i class="bi bi-printer"></i>
                                </a>
                                {{-- EDIT --}}
                                <a href="{{ route('transaksi-keluar.edit', $trx->id) }}"
                                    class="btn btn-sm btn-warning text-white" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                {{-- HAPUS --}}
                                <form action="{{ route('transaksi-keluar.destroy', $trx->id) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Hapus transaksi ini? Stok barang akan dikembalikan ke gudang.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
