<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 5 Metrik Ringkasan (Slide 29 P4 & Dashboard P5)
        $totalProducts   = Product::count();
        $inventoryValue  = Product::sum(DB::raw('stock * price'));
        $lowStockCount   = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $totalIn         = StockMovement::where('type', 'IN')->sum('quantity');
        $totalOut        = StockMovement::where('type', 'OUT')->sum('quantity');

        // Ringkasan per Kategori (Slide 14 P4)
        $categorySummary = Category::withCount('products')
            ->withSum('products as total_stock', 'stock')
            ->withAvg('products as avg_price', 'price')
            ->get();

        // Barang Stok Rendah
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->get();

        return view('dashboard', compact(
            'totalProducts', 'inventoryValue', 'lowStockCount', 
            'totalIn', 'totalOut', 'categorySummary', 'lowStockProducts'
        ));
    }
}
