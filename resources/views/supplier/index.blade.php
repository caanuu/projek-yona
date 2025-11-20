@extends('layout')
@section('title', 'Data Supplier')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Data Supplier</h2>
        <a href="{{ route('supplier.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Supplier
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Supplier</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $index => $s)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $s->nama_supplier }}</td>
                                <td>{{ $s->alamat ?? '-' }}</td>
                                <td>{{ $s->telp ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('supplier.edit', $s->id) }}" class="btn btn-sm btn-warning"
                                            title="Edit">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>

                                        <form action="{{ route('supplier.destroy', $s->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus supplier {{ $s->nama_supplier }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data supplier. Silakan tambah baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
