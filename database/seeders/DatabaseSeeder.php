<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Sesuai Slide 30 Pertemuan 5
     *
     * @return void
     */
    public function run()
    {
        // 1. Buat User Awal persis P4 (admin/admin123 dan staff/staff123)
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Gudang',
                'email' => 'admin@inventory.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $staff = User::firstOrCreate(
            ['username' => 'staff'],
            [
                'name' => 'Staff Petugas Lapangan',
                'email' => 'staff@inventory.com',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
            ]
        );

        // 2. Buat Kategori
        $cat1 = Category::firstOrCreate(['name' => 'Elektronik & Gadget'], ['description' => 'Perangkat keras komputer dan periferal']);
        $cat2 = Category::firstOrCreate(['name' => 'Perlengkapan Kantor'], ['description' => 'Alat tulis dan kebutuhan administrasi']);
        $cat3 = Category::firstOrCreate(['name' => 'Aksesoris Jaringan'], ['description' => 'Kabel LAN, router, dan konektor']);

        // 3. Buat Supplier
        $sup1 = Supplier::firstOrCreate(['name' => 'PT Megah Teknologi'], ['contact_person' => 'Budi Santoso', 'phone' => '021-5551234', 'email' => 'budi@megah.com']);
        $sup2 = Supplier::firstOrCreate(['name' => 'CV Maju Lancar Logistik'], ['contact_person' => 'Siti Rahma', 'phone' => '022-7778899', 'email' => 'siti@majulancar.id']);
        $sup3 = Supplier::firstOrCreate(['name' => 'Global Partsindo'], ['contact_person' => 'Hendra Tan', 'phone' => '031-4445566', 'email' => 'hendra@globalparts.com']);

        // 4. Buat Produk Awal
        $products = [
            ['category_id' => $cat1->id, 'sku' => 'PRD-KB-001', 'name' => 'Keyboard Mechanical RGB', 'price' => 350000, 'stock' => 15, 'minimum_stock' => 5],
            ['category_id' => $cat1->id, 'sku' => 'PRD-MS-002', 'name' => 'Wireless Mouse Ergonomic', 'price' => 125000, 'stock' => 4, 'minimum_stock' => 10],
            ['category_id' => $cat1->id, 'sku' => 'PRD-MN-003', 'name' => 'Monitor LED 24 Inch Full HD', 'price' => 1450000, 'stock' => 2, 'minimum_stock' => 5],
            ['category_id' => $cat2->id, 'sku' => 'PRD-PP-004', 'name' => 'Kertas HVS A4 80gr 1 Rim', 'price' => 55000, 'stock' => 30, 'minimum_stock' => 10],
            ['category_id' => $cat3->id, 'sku' => 'PRD-CB-005', 'name' => 'Kabel UTP Cat6 305 Meter', 'price' => 890000, 'stock' => 8, 'minimum_stock' => 3],
        ];

        foreach ($products as $pData) {
            $product = Product::firstOrCreate(['sku' => $pData['sku']], $pData);

            // Hubungkan Many-to-Many Supplier
            $product->suppliers()->syncWithoutDetaching([
                $sup1->id => ['supplier_price' => $pData['price'] * 0.8],
            ]);

            // Catat transaksi saldo awal
            StockMovement::firstOrCreate(
                ['product_id' => $product->id, 'type' => 'IN', 'notes' => 'Saldo awal stok produk'],
                [
                    'user_id' => $admin->id,
                    'quantity' => $pData['stock'],
                ]
            );
        }
    }
}
