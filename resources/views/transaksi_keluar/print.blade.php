<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Faktur #{{ $transaksi->kode_transaksi }} - PT. Surya Sukses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #e2e8f0;
            padding: 20px;
            font-family: 'Arial', sans-serif;
        }

        .invoice-box {
            background: #fff;
            max-width: 800px;
            margin: auto;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .header-title {
            color: #0f172a;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .company-info {
            font-size: 0.9rem;
            color: #64748b;
        }

        .invoice-details {
            text-align: right;
        }

        .table-head {
            background-color: #f1f5f9;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .invoice-box {
                box-shadow: none;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="invoice-box" id="print-area">
        <div class="row mb-5 align-items-center">
            <div class="col-7">
                <h2 class="header-title mb-0 text-primary">PT. SURYA SUKSES</h2>
                <h5 class="fw-bold text-dark">ELEKTRONIK</h5>
                <div class="company-info mt-2">
                    Jl. Elektronik Raya No. 123, Jakarta<br>
                    Telp: (021) 555-7777 | Email: info@suryasukses.com
                </div>
            </div>
            <div class="col-5 invoice-details">
                <h3 class="fw-bold text-uppercase text-secondary mb-1">FAKTUR</h3>
                <p class="mb-0">No: <strong>#{{ $transaksi->kode_transaksi }}</strong></p>
                <p class="mb-0">Tanggal: {{ $transaksi->created_at->format('d/m/Y') }}</p>
                <p>Kasir: {{ $transaksi->user->name ?? '-' }}</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="p-3 bg-light rounded">
                    <strong class="text-uppercase text-muted small">Kepada Yth:</strong><br>
                    <span class="fs-5 fw-bold">{{ $transaksi->customer }}</span>
                    @if ($transaksi->keterangan_keluar)
                        <br><small class="text-muted">Catatan: {{ $transaksi->keterangan_keluar }}</small>
                    @endif
                </div>
            </div>
        </div>

        <table class="table table-bordered border-light">
            <thead class="table-head">
                <tr>
                    <th class="py-3">No</th>
                    <th class="py-3">Nama Barang</th>
                    <th class="py-3 text-center">Qty</th>
                    <th class="py-3 text-end">Harga</th>
                    <th class="py-3 text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->details as $i => $detail)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $detail->barang->nama_barang }}</td>
                        <td class="text-center">{{ $detail->jumlah }}</td>
                        <td class="text-end">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold">Rp
                            {{ number_format($detail->jumlah * $detail->harga_jual, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end py-3 text-muted text-uppercase fw-bold">Total Tagihan</td>
                    <td class="text-end py-3 fs-5 fw-bold text-primary">
                        Rp
                        {{ number_format($transaksi->details->sum(fn($d) => $d->jumlah * $d->harga_jual), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-5 pt-4 border-top text-center text-muted small">
            <p class="mb-1">Terima kasih atas kepercayaan Anda berbelanja di PT. Surya Sukses Elektronik.</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.</p>
        </div>
    </div>

    <div class="text-center mt-4 no-print">
        <button class="btn btn-primary btn-lg shadow" onclick="printInvoice()">
            <i class="bi bi-printer-fill me-2"></i> Cetak Faktur
        </button>
        <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary btn-lg ms-2">Kembali</a>
    </div>

    <script>
        function printInvoice() {
            window.print();
        }
    </script>

</body>

</html>
