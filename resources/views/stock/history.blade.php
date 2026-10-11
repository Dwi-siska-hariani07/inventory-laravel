@extends('layouts.app')

@section('title', 'Histori Transaksi Stok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Histori Transaksi Stok</h3>
        <p class="text-muted small mb-0">Catatan pergerakan barang masuk dan keluar (Audit Trail)</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('stock.in') }}" class="btn btn-success btn-sm"><i class="bi bi-box-arrow-in-down me-1"></i> Input Masuk</a>
        <a href="{{ route('stock.out') }}" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i> Input Keluar</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Waktu</th>
                    <th>SKU & Produk</th>
                    <th>Kategori</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-center">Jumlah</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @if($movements->count() > 0)
                @foreach($movements as $m)
                <tr>
                    <td class="small text-muted">{{ $m->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        <div class="fw-semibold">{{ $m->product->name ?? 'Produk Dihapus' }}</div>
                        <small class="text-muted font-monospace">{{ $m->product->sku ?? '-' }}</small>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $m->product->category->name ?? '-' }}</span></td>
                    <td class="text-center">
                        <span class="badge {{ $m->type === 'IN' ? 'bg-success' : 'bg-danger' }}">
                            {{ $m->type === 'IN' ? 'MASUK' : 'KELUAR' }}
                        </span>
                    </td>
                    <td class="text-center fw-bold fs-6 {{ $m->type === 'IN' ? 'text-success' : 'text-danger' }}">
                        {{ ($m->type === 'IN' ? '+' : '-') . $m->quantity }}
                    </td>
                    <td class="small">{{ $m->notes ?? '-' }}</td>
                </tr>
                @endforeach
                @else
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    @if($movements->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $movements->links() }}
    </div>
    @endif
</div>
@endsection
