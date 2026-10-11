@extends('layouts.app')

@section('title', 'Dashboard Ringkasan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Dashboard Ringkasan</h3>
        <p class="text-muted small mb-0">Informasi ringkas dan laporan agregat untuk pengambilan keputusan bisnis</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('stock.in') }}" class="btn btn-success btn-sm">
            <i class="bi bi-box-arrow-in-down me-1"></i> Input Masuk (IN)
        </a>
        <a href="{{ route('stock.out') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> Input Keluar (OUT)
        </a>
    </div>
</div>

<!-- 5 Widget Sesuai Slide 29 -->
<div class="row g-3 mb-4">
    <div class="col-md-4 col-xl">
        <div class="card h-100 shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-bold">Total Produk</div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <h2 class="fw-bold mb-0">{{ number_format($totalProducts) }}</h2>
                    <i class="bi bi-box-seam text-primary fs-2"></i>
                </div>
                <small class="text-muted">COUNT(products.id)</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xl">
        <div class="card h-100 shadow-sm border-0 border-start border-success border-4">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-bold">Nilai Inventory</div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($inventoryValue, 0, ',', '.') }}</h3>
                    <i class="bi bi-cash-stack text-success fs-2"></i>
                </div>
                <small class="text-muted">SUM(stock &times; price)</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xl">
        <div class="card h-100 shadow-sm border-0 border-start border-danger border-4">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-bold">Produk Stok Rendah</div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <h2 class="fw-bold mb-0 text-danger">{{ number_format($lowStockCount) }}</h2>
                    <i class="bi bi-exclamation-triangle text-danger fs-2"></i>
                </div>
                <small class="text-muted">stock &le; minimum_stock</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl">
        <div class="card h-100 shadow-sm border-0 border-start border-info border-4">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-bold">Barang Masuk</div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <h2 class="fw-bold mb-0 text-info">{{ number_format($totalIn) }}</h2>
                    <i class="bi bi-arrow-down-left-square text-info fs-2"></i>
                </div>
                <small class="text-muted">SUM(quantity) [IN]</small>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl">
        <div class="card h-100 shadow-sm border-0 border-start border-warning border-4">
            <div class="card-body">
                <div class="text-muted small text-uppercase fw-bold">Barang Keluar</div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <h2 class="fw-bold mb-0 text-warning">{{ number_format($totalOut) }}</h2>
                    <i class="bi bi-arrow-up-right-square text-warning fs-2"></i>
                </div>
                <small class="text-muted">SUM(quantity) [OUT]</small>
            </div>
        </div>
    </div>
</div>

<!-- Peringatan Stok Rendah -->
@if($lowStockProducts->count() > 0)
<div class="alert alert-danger shadow-sm mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-bell-fill fs-5 me-2"></i>
        <strong class="fs-6">Peringatan: Ada {{ $lowStockProducts->count() }} produk yang mencapai atau berada di bawah batas minimum stok!</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-danger table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Stok Saat Ini</th>
                    <th>Batas Minimum</th>
                    <th>Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $l)
                <tr>
                    <td class="fw-bold">{{ $l->sku }}</td>
                    <td>{{ $l->name }}</td>
                    <td><span class="badge bg-secondary">{{ $l->category->name ?? '-' }}</span></td>
                    <td class="text-danger fw-bold">{{ $l->stock }} unit</td>
                    <td>{{ $l->minimum_stock }} unit</td>
                    <td>
                        <a href="{{ route('stock.in') }}?product_id={{ $l->id }}" class="btn btn-xs btn-outline-danger btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Restock
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="row g-4">
    <!-- Laporan Kategori & Fungsi Agregat (Slide 14) -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <span><i class="bi bi-pie-chart me-2"></i> Ringkasan Kategori (GROUP BY & Aggregate)</span>
                <span class="badge bg-primary">Slide 14</span>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kategori</th>
                            <th class="text-center">Jml Produk</th>
                            <th class="text-center">Total Stok</th>
                            <th class="text-end">Rata-rata Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($categorySummary->count() > 0)
                        @foreach($categorySummary as $cs)
                        <tr>
                            <td class="fw-semibold">{{ $cs->name }}</td>
                            <td class="text-center"><span class="badge bg-info text-dark">{{ $cs->products_count }}</span></td>
                            <td class="text-center fw-bold">{{ number_format($cs->total_stock ?? 0) }}</td>
                            <td class="text-end">Rp {{ number_format($cs->avg_price ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada kategori.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Laporan Akumulasi Transaksi Produk (Slide 28) -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <span><i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan Arus Masuk vs Keluar</span>
                <span class="badge bg-primary">Slide 28</span>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>SKU & Produk</th>
                            <th class="text-center text-success">Total IN</th>
                            <th class="text-center text-danger">Total OUT</th>
                            <th class="text-center">Stok Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $productMovements = \App\Models\Product::with(['stockMovements'])->limit(10)->get();
                        @endphp
                        @if($productMovements->count() > 0)
                        @foreach($productMovements as $pm)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $pm->name }}</div>
                                <small class="text-muted">{{ $pm->sku }}</small>
                            </td>
                            <td class="text-center text-success fw-bold">+{{ number_format($pm->stockMovements->where('type', 'IN')->sum('quantity')) }}</td>
                            <td class="text-center text-danger fw-bold">-{{ number_format($pm->stockMovements->where('type', 'OUT')->sum('quantity')) }}</td>
                            <td class="text-center">
                                <span class="badge {{ $pm->stock <= $pm->minimum_stock ? 'bg-danger' : 'bg-success' }}">
                                    {{ number_format($pm->stock) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data transaksi produk.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-muted small">
                <em>Rumus konsistensi stok: <code>stok akhir = stok awal + total masuk - total keluar</code></em>
            </div>
        </div>
    </div>
</div>
@endsection
