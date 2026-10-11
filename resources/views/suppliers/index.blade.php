@extends('layouts.app')

@section('title', 'Master Supplier')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Master Data Supplier</h3>
        <p class="text-muted small mb-0">Relasi Many-to-Many dengan Produk melalui tabel <code>product_suppliers</code></p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Tambah Supplier -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-plus-circle text-primary me-1"></i> Tambah Supplier</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('suppliers.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Supplier / PT <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="PT Contoh Logistik" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kontak PIC</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}" placeholder="Nama penanggung jawab">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="supplier@email.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Alamat</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Alamat kantor / gudang...">{{ old('address') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-1"></i> Simpan Supplier
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Supplier -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-truck me-1"></i> Daftar Supplier</h6>
                <span class="badge bg-secondary">{{ $suppliers->count() }} Total</span>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Supplier</th>
                            <th>PIC & Kontak</th>
                            <th>Alamat</th>
                            <th class="text-center">Jml Produk Dipasok</th>
                            <th class="text-center" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($suppliers->count() > 0)
                        @foreach($suppliers as $idx => $s)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="fw-semibold">{{ $s->name }}</td>
                            <td class="small">
                                <div><strong>PIC:</strong> {{ $s->contact_person ?? '-' }}</div>
                                <div class="text-muted"><i class="bi bi-phone"></i> {{ $s->phone ?? '-' }} &bull; <i class="bi bi-envelope"></i> {{ $s->email ?? '-' }}</div>
                            </td>
                            <td class="small text-muted">{{ $s->address ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $s->products_count }} produk</span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('suppliers.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus supplier ini? Pasangan produk-supplier akan terhapus otomatis.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data supplier.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
