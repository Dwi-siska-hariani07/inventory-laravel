@extends('layouts.app')

@section('title', 'Master Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Master Data Kategori</h3>
        <p class="text-muted small mb-0">Relasi One-to-Many: 1 Kategori memiliki N Produk (Eloquent)</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Tambah Kategori -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-plus-circle text-primary me-1"></i> Tambah Kategori Baru</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Aksesoris Komputer" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Keterangan singkat kategori...">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-1"></i> Simpan Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Kategori -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-ul me-1"></i> Daftar Kategori</h6>
                <span class="badge bg-secondary">{{ $categories->count() }} Total</span>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Jml Produk</th>
                            <th class="text-center">Total Stok</th>
                            <th class="text-center" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($categories->count() > 0)
                        @foreach($categories as $idx => $c)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="fw-semibold">
                                <a href="{{ route('products.index', ['category_id' => $c->id]) }}" class="text-decoration-none">
                                    {{ $c->name }}
                                </a>
                            </td>
                            <td class="small text-muted">{{ $c->description ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">{{ $c->products_count }}</span>
                            </td>
                            <td class="text-center fw-bold">{{ number_format($c->total_stock ?? 0) }}</td>
                            <td class="text-center">
                                <form action="{{ route('categories.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini? Database akan menolak jika masih digunakan produk aktif.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada kategori.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
