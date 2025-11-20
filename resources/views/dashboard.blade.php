@extends('layout')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="container-fluid p-0">

        <!-- Filter Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Ringkasan Bisnis</h4>
                <p class="text-muted mb-0">Pantau performa gudang dan penjualan Anda.</p>
            </div>
            <form method="GET" class="d-flex gap-2 bg-white p-1 rounded-3 shadow-sm border">
                <select name="filter" class="form-select form-select-sm border-0 fw-medium bg-light" style="width: 120px;">
                    <option value="day" {{ request('filter') == 'day' ? 'selected' : '' }}>Harian</option>
                    <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>Mingguan</option>
                    <option value="month" {{ request('filter', 'month') == 'month' ? 'selected' : '' }}>Bulanan</option>
                    <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>Tahunan</option>
                </select>
                <button class="btn btn-primary btn-sm px-3 rounded-2">Filter</button>
            </form>
        </div>

        <div class="alert alert-light border-primary border shadow-sm mb-4 d-flex align-items-center" role="alert">
            <i class="bi bi-info-circle-fill text-primary me-2"></i>
            <div>
                Menampilkan data untuk periode: <strong>{{ $label }}</strong>
            </div>
        </div>

        {{-- Kartu Statistik Utama --}}
        <div class="row g-4 mb-5">
            <!-- Total Penjualan -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted text-uppercase fw-bold small mb-1">Total Pendapatan</p>
                                <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                                <i class="bi bi-wallet2 fs-3"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-2">
                                <i class="bi bi-arrow-up-short"></i> Penjualan
                            </span>
                            <span class="text-muted small ms-2">Periode ini</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barang Terjual -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted text-uppercase fw-bold small mb-1">Unit Terjual</p>
                                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalBarangTerjual) }} <span
                                        class="fs-6 text-muted fw-normal">Pcs</span></h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success">
                                <i class="bi bi-box-seam fs-3"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-muted small">Total barang keluar dari gudang.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jumlah Transaksi -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted text-uppercase fw-bold small mb-1">Total Transaksi</p>
                                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalTransaksi) }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning">
                                <i class="bi bi-receipt fs-3"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-muted small">Jumlah faktur yang diterbitkan.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik & Tabel --}}
        <div class="row g-4">
            <!-- Grafik -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="fw-bold mb-0">📈 Tren Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Barang Terlaris -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div
                        class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">🔥 Top 5 Produk</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($barangTerlaris as $index => $item)
                                <div class="list-group-item border-0 px-4 py-3 d-flex align-items-center">
                                    <div class="me-3 fw-bold text-secondary">#{{ $index + 1 }}</div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">{{ $item->barang->nama_barang }}</div>
                                        <small class="text-muted">Terjual</small>
                                    </div>
                                    <div class="text-end">
                                        <h6 class="fw-bold text-primary mb-0">{{ $item->total_terjual }}</h6>
                                        <small class="text-muted">Unit</small>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">Belum ada data penjualan.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Stok Bawah --}}
        <div class="row g-4 mt-2 mb-5">
            <!-- Total Stok -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="fw-bold mb-0">📦 Stok Barang (Baik)</h5>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-secondary small text-uppercase">Nama Barang</th>
                                    <th class="px-4 py-3 text-end text-secondary small text-uppercase">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listBarangStok->take(6) as $item)
                                    <tr>
                                        <td class="px-4">{{ $item->nama_barang }}</td>
                                        <td class="px-4 text-end">
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                {{ $item->stok_baik }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="p-3 text-center border-top">
                            <a href="{{ route('barang.list') }}" class="text-decoration-none fw-semibold small">Lihat Semua
                                Stok &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stok Rusak & Menipis Gabungan -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="fw-bold mb-0 text-danger">⚠️ Perlu Perhatian (Rusak/Menipis)</h5>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-secondary small text-uppercase">Nama Barang</th>
                                    <th class="px-4 py-3 text-secondary small text-uppercase">Status</th>
                                    <th class="px-4 py-3 text-end text-secondary small text-uppercase">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Gabungkan data menipis dan rusak untuk efisiensi tampilan --}}
                                @foreach ($stokMenipis->take(3) as $m)
                                    <tr>
                                        <td class="px-4">{{ $m->nama_barang }}</td>
                                        <td class="px-4"><span class="badge bg-warning text-dark">Stok Menipis</span>
                                        </td>
                                        <td class="px-4 text-end fw-bold">{{ $m->stok_baik }}</td>
                                    </tr>
                                @endforeach
                                @foreach ($rusak->take(3) as $r)
                                    <tr>
                                        <td class="px-4">{{ $r->nama_barang }}</td>
                                        <td class="px-4"><span class="badge bg-danger">Rusak</span></td>
                                        <td class="px-4 text-end fw-bold text-danger">{{ $r->stok_rusak }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="p-3 text-center border-top">
                            <a href="{{ route('barang.rusak') }}"
                                class="text-decoration-none fw-semibold small text-danger">Lihat Detail Barang Rusak
                                &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js Config --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');

        // Gradient for Chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)'); // Primary color with opacity
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($dateStrings),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json(array_values($salesData)),
                    borderColor: '#2563eb',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: {
                            size: 13
                        },
                        bodyFont: {
                            size: 14
                        },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let value = context.raw;
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [5, 5],
                            color: '#e2e8f0'
                        },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'Jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                                return value;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
