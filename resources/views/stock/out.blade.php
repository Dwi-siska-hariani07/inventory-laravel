@extends('layouts.app')

@section('title', 'Barang Keluar (OUT)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-danger text-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-box-arrow-up-right me-2"></i> Transaksi Barang Keluar (OUT)</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('stock.storeOut') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                    [{{ $p->sku }}] {{ $p->name }} (Tersedia: {{ $p->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Keluar (Unit) <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" min="1" placeholder="Jumlah unit..." required>
                        <small class="text-danger">Business Rule: Tidak boleh melebihi stok yang tersedia.</small>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan / Alasan Pengeluaran</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Contoh: Distribusi ke kantor cabang...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-danger px-4 fw-semibold">
                            <i class="bi bi-dash-circle me-1"></i> Simpan Transaksi Keluar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
