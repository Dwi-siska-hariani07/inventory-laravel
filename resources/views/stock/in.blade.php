@extends('layouts.app')

@section('title', 'Barang Masuk (IN)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white py-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-box-arrow-in-down me-2"></i> Transaksi Barang Masuk (IN)</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('stock.storeIn') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                                    [{{ $p->sku }}] {{ $p->name }} (Stok saat ini: {{ $p->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Masuk (Unit) <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" min="1" placeholder="Jumlah unit..." required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan / No. Surat Jalan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Keterangan pengadaan...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Transaksi Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
