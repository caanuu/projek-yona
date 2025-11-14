@extends('layout')

@section('title', 'Edit Barang')

@section('content')
    <h2 class="mb-4">Edit Barang</h2>

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

    <form action="{{ route('barang.update', $barang->id) }}" method="POST" class="card p-4 mb-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Kode Barang</label>
            <input type="text" name="kode_barang" class="form-control" value="{{ old('kode_barang', $barang->kode_barang) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang', $barang->nama_barang) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jenis Barang</label>
            <input type="text" name="jenis_barang" class="form-control" value="{{ old('jenis_barang', $barang->jenis_barang) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control">{{ old('keterangan', $barang->keterangan) }}</textarea>
        </div>

        <p>Stok Saat Ini: Baik: {{ $barang->stok_baik }}, Rusak: {{ $barang->stok_rusak }}</p>

        {{-- Halaman ini hanya untuk edit data master barang. --}}
        {{-- Untuk mengubah stok (baik/rusak), gunakan menu "Mutasi Stok" --}}

        <hr class="my-3">

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>

    @if($mutasiKondisis->count() > 0)
        <h4>Riwayat Mutasi Kondisi</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-secondary">
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Dari Status</th>
                    <th>Ke Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mutasiKondisis as $mutasi)
                    <tr>
                        {{-- Pastikan tanggal diformat --}}
                        <td>{{ \Carbon\Carbon::parse($mutasi->tanggal)->format('d M Y') }}</td>
                        <td>{{ $mutasi->jumlah }}</td>
                        <td>{{ $mutasi->from_status }}</td>
                        <td>{{ $mutasi->to_status }}</td>
                        <td>{{ $mutasi->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Belum ada riwayat mutasi kondisi untuk barang ini.</p>
    @endif
@endsection
