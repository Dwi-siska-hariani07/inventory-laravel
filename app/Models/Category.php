<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relationship: One-to-Many
     * 1 Kategori memiliki banyak Produk (Slide 33)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
