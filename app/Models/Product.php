<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Sesuai Slide 28
     */
    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'price',
        'stock',
        'minimum_stock',
    ];

    /**
     * Relationship: Many-to-One
     * Produk dimiliki oleh satu Kategori (Slide 33)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Many-to-Many
     * Produk memiliki banyak Supplier melalui product_suppliers
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers')
                    ->withPivot('supplier_price')
                    ->withTimestamps();
    }

    /**
     * Relationship: One-to-Many
     * Produk memiliki banyak riwayat transaksi stok
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Helper cek apakah stok di bawah minimum
     */
    public function isLowStock()
    {
        return $this->stock <= $this->minimum_stock;
    }
}
