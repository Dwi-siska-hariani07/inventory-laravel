<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * Sesuai Slide 17, 32, 38
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $sortBy = in_array($request->query('sort'), ['name', 'price', 'stock', 'sku', 'id']) ? $request->query('sort') : 'id';
        $order = in_array(strtoupper($request->query('order')), ['ASC', 'DESC']) ? $request->query('order') : 'DESC';

        // Eager Loading with('category') untuk mencegah N+1 query (Slide 34)
        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy($sortBy, $order)
            ->paginate(10)
            ->withQueryString(); // Mempertahankan parameter filter (Slide 32)

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoryId', 'sortBy', 'order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     * Sesuai Slide 19
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'   => ['required', 'exists:categories,id'],
            'sku'           => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name'          => ['required', 'string', 'max:150'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:1'],
            'supplier_ids'  => ['nullable', 'array'],
            'supplier_ids.*'=> ['exists:suppliers,id'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $product = Product::create($validated);

            if (!empty($request->supplier_ids)) {
                $syncData = [];
                foreach ($request->supplier_ids as $sId) {
                    $syncData[$sId] = ['supplier_price' => $validated['price'] * 0.8];
                }
                $product->suppliers()->sync($syncData);
            }

            if ($validated['stock'] > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'type'       => 'IN',
                    'quantity'   => $validated['stock'],
                    'notes'      => 'Saldo awal master produk baru',
                ]);
            }
        });

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Implicit route model binding (Slide 14)
     */
    public function show(Product $product)
    {
        $product->load(['category', 'suppliers', 'stockMovements' => function ($q) {
            $q->latest();
        }]);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'   => ['required', 'exists:categories,id'],
            'sku'           => ['required', 'string', 'max:50', 'unique:products,sku,' . $product->id],
            'name'          => ['required', 'string', 'max:150'],
            'price'         => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:1'],
            'supplier_ids'  => ['nullable', 'array'],
        ]);

        DB::transaction(function () use ($product, $validated, $request) {
            $product->update($validated);

            if ($request->has('supplier_ids')) {
                $syncData = [];
                foreach ((array)$request->supplier_ids as $sId) {
                    $syncData[$sId] = ['supplier_price' => $validated['price'] * 0.8];
                }
                $product->suppliers()->sync($syncData);
            }
        });

        return redirect()->route('products.index')
                         ->with('success', 'Data produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Business Rule P4: Produk yang punya histori tidak boleh dihapus sembarangan
     */
    public function destroy(Product $product)
    {
        if ($product->stockMovements()->count() > 0) {
            return redirect()->route('products.index')
                             ->with('error', 'Gagal menghapus! Produk ini memiliki riwayat transaksi pergerakan stok.');
        }

        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }
}
