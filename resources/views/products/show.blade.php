@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">{{ $product->name }}</h3>
        <p class="text-muted small mb-0">SKU: <span class="badge bg-light text-dark border font-monospace">{{ $product->sku }}</span> &bull; Kategori: {{ $product->category->name ?? '-' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">&larr; Kembali</a>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><i class="bi bi-info-circle me-1"></i> Info Produk</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Harga</span>
                        <strong class="text-success fs-5">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Stok Tersedia</span>
                        <span class="badge {{ $product->isLowStock() ? 'bg-danger' : 'bg-primary' }} fs-6">{{ $product->stock }} unit</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Batas Minimum Stok</span>
                        <span>{{ $product->minimum_stock }} unit</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-truck me-1"></i> Supplier Pemasok</span>
                <span class="badge bg-secondary">Many-to-Many</span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @if($product->suppliers->count() > 0)
                    @foreach($product->suppliers as $sup)
                    <li class="list-group-item">
                        <div class="fw-semibold">{{ $sup->name }}</div>
                        <small class="text-muted"><i class="bi bi-telephone"></i> {{ $sup->phone ?? '-' }} &bull; {{ $sup->contact_person ?? '-' }}</small>
                    </li>
                    @endforeach
                    @else
                    <li class="list-group-item text-muted small text-center">Belum ada supplier terhubung.</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-1"></i> Riwayat Transaksi Stok</span>
                <span class="badge bg-info text-dark">{{ $product->stockMovements->count() }} Data</span>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($product->stockMovements->count() > 0)
                        @foreach($product->stockMovements as $sm)
                        <tr>
                            <td class="small text-muted">{{ $sm->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <span class="badge {{ $sm->type === 'IN' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $sm->type }}
                                </span>
                            </td>
                            <td class="text-center fw-bold {{ $sm->type === 'IN' ? 'text-success' : 'text-danger' }}">
                                {{ ($sm->type === 'IN' ? '+' : '-') . $sm->quantity }}
                            </td>
                            <td class="small">{{ $sm->notes ?? '-' }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada histori transaksi.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
