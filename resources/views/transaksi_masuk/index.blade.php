@extends('layout')

@section('title', 'Transaksi Masuk')

@section('content')

    {{-- FILTER --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('transaksi-masuk.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="filter" class="form-label">Filter Berdasarkan</label>
                    <select name="filter" id="filter" class="form-select" onchange="this.form.submit()">
                        <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Mingguan</option>
                        <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Bulanan</option>
                        <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
                {{-- ... (Filter logic sama, disingkat agar fokus ke tabel) ... --}}
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL TRANSAKSI MASUK --}}
    <div class="d-flex justify-content-between mb-3">
        <h2>Transaksi Masuk</h2>
        <div class="btn-group">
            {{-- Tombol Export/Print --}}
            <a href="{{ route('transaksi-masuk.export', request()->query()) }}" class="btn btn-success">📤 Excel</a>
        </div>
        <a href="{{ route('transaksi-masuk.create') }}" class="btn btn-success">+ Tambah Transaksi</a>
    </div>

    <div id="print-area">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Supplier</th> {{-- Tampilkan Nama Supplier --}}
                        <th>Pegawai</th> {{-- Tampilkan Nama User --}}
                        <th>Keterangan</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Beli</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($transaksiMasuks as $trx)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $trx->kode_transaksi }}</td>
                            <td>{{ $trx->created_at->format('d M Y') }}</td>

                            {{-- Relasi ke Supplier --}}
                            <td>{{ $trx->supplier ? $trx->supplier->nama_supplier : 'Supplier Dihapus' }}</td>

                            {{-- Relasi ke User --}}
                            <td>{{ $trx->user ? $trx->user->name : 'User Dihapus' }}</td>

                            <td>{{ $trx->keterangan_masuk }}</td>

                            {{-- Detail Barang --}}
                            <td>
                                <ul class="mb-0 ps-3">
                                    @foreach ($trx->details as $detail)
                                        <li>{{ $detail->barang->nama_barang }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="mb-0 list-unstyled">
                                    @foreach ($trx->details as $detail)
                                        <li>{{ $detail->jumlah }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="mb-0 list-unstyled">
                                    @foreach ($trx->details as $detail)
                                        <li>Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
