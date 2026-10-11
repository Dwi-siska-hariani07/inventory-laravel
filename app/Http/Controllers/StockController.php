<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function history()
    {
        $movements = StockMovement::with(['product.category', 'user'])
            ->latest()
            ->paginate(15);

        return view('stock.history', compact('movements'));
    }

    public function createIn()
    {
        $products = Product::orderBy('name')->get();
        return view('stock.in', compact('products'));
    }

    public function storeIn(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'notes'      => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $product->increment('stock', $validated['quantity']);

            StockMovement::create([
                'product_id' => $product->id,
                'type'       => 'IN',
                'quantity'   => $validated['quantity'],
                'notes'      => $validated['notes'] ?? 'Barang masuk ke gudang',
            ]);
        });

        return redirect()->route('stock.history')->with('success', 'Barang masuk berhasil dicatat!');
    }

    public function createOut()
    {
        $products = Product::orderBy('name')->get();
        return view('stock.out', compact('products'));
    }

    public function storeOut(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'notes'      => ['nullable', 'string', 'max:255'],
        ]);

        $errorMsg = null;

        DB::transaction(function () use ($validated, &$errorMsg) {
            // SELECT FOR UPDATE (Slide 26 P4)
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

            // Business rule anti-stok negatif
            if ($validated['quantity'] > $product->stock) {
                $errorMsg = "Stok tidak mencukupi! Tersisa {$product->stock} unit.";
                return;
            }

            $product->decrement('stock', $validated['quantity']);

            StockMovement::create([
                'product_id' => $product->id,
                'type'       => 'OUT',
                'quantity'   => $validated['quantity'],
                'notes'      => $validated['notes'] ?? 'Pengeluaran barang dari gudang',
            ]);
        });

        if ($errorMsg) {
            return back()->withInput()->with('error', $errorMsg);
        }

        return redirect()->route('stock.history')->with('success', 'Pengeluaran barang berhasil dicatat!');
    }
}
