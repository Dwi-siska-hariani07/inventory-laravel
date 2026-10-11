@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Manajemen Produk</h3>
        <p class="text-muted small mb-0">Katalog master data produk, relasi kategori, serta kontrol stok</p>
    </div>
    @if(auth()->check() && auth()->user()->role === 'admin')
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
    </a>
    @endif
</div>

<!-- Filter & Search Toolbar (Slide 16) -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama produk / SKU..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>Urut: Terbaru</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Urut: Nama</option>
                    <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Urut: Harga</option>
                    <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }}>Urut: Stok</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="order" class="form-select">
                    <option value="ASC" {{ request('order') == 'ASC' ? 'selected' : '' }}>Ascending (A-Z / Min)</option>
                    <option value="DESC" {{ (request('order') == 'DESC' || !request('order')) ? 'selected' : '' }}>Descending (Z-A / Max)</option>
                </select>
            </div>

            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-secondary w-100" title="Terapkan Filter">
                    Filter
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Daftar Produk -->
<div class="card shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Min. Stok</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($products->count() > 0)
                    @foreach($products as $idx => $p)
                    <tr>
                        <td>{{ ($products->currentPage() - 1) * $products->perPage() + $idx + 1 }}</td>
                        <td><span class="badge bg-light text-dark border font-monospace">{{ $p->sku }}</span></td>
                        <td class="fw-semibold">
                            <a href="{{ route('products.show', $p) }}" class="text-decoration-none text-dark">
                                {{ $p->name }}
                            </a>
                        </td>
                        <td><span class="badge bg-info text-dark">{{ $p->category->name ?? '-' }}</span></td>
                        <td class="text-end fw-semibold">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                        <td class="text-center fw-bold fs-6">
                            {{ number_format($p->stock) }}
                        </td>
                        <td class="text-center text-muted">{{ number_format($p->minimum_stock) }}</td>
                        <td class="text-center">
                            @if($p->stock <= 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($p->isLowStock())
                                <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Menipis</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('products.show', $p) }}" class="btn btn-outline-info" title="Lihat Detail & Relasi Supplier">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->check() && auth()->user()->role === 'admin')
                                <a href="{{ route('products.edit', $p) }}" class="btn btn-outline-warning" title="Edit Produk">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus Produk"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-1"></i> Tidak ada produk yang sesuai dengan kriteria pencarian.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
        <small class="text-muted">
            Menampilkan data ke-{{ $products->firstItem() }} s/d {{ $products->lastItem() }} dari total {{ $products->total() }} produk
        </small>
        <div>
            {{ $products->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
