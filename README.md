# 📦 Inventory Management System (Laravel Edition)
> **Mata Kuliah:** Web Programming / Relational Database + Advanced Web  
> **Pertemuan 5:** Laravel Fundamental — *Mengubah Inventory Management System P4 Menjadi Laravel Application*

---

## 📌 1. Pendahuluan & Posisi Pertemuan 5 (Sesuai Slide 02)

Proyek ini merupakan hasil **refactoring dan migrasi penuh** dari proyek **Pertemuan 4 (PHP Native)** menuju **Framework Laravel 8 berbasis arsitektur MVC (Model-View-Controller)**.

> **Prinsip Utama Dosen (Slide 02 & 35):**
> *"Pertemuan 5 tidak membuat project baru. Mahasiswa memindahkan dan merapikan project P4 ke Laravel. Konsep database, tampilan antarmuka, dan business logic tetap dipertahankan."*

---

## 🏗️ 2. Penjelasan Arsitektur MVC & Alur Data (Slide 10–11)

Sistem bekerja berdasarkan siklus hidup permintaan (*Request Lifecycle*) yang bersih dan terstruktur:

```text
[ Browser / User ]
        │
        ▼ (HTTP Request: GET /products)
[ routes/web.php ] ─── Mengarahkan request ke Resource Controller
        │
        ▼ (Route::resource)
[ ProductController.php ] ─── Menerima input, validasi ($request->validate)
        │
        ▼ (Eloquent ORM)
[ Models (Product, Category, dll.) ] ─── Mengelola relasi data & query
        │
        ▼ (Query Builder / PDO)
[ MySQL Database (inventory_db) ]
        │
        ▼ (Data Result)
[ Blade Views (resources/views/...) ] ─── Merender layout HTML aman (Anti-XSS)
        │
        ▼ (HTTP Response)
[ Browser / Tampilan Antarmuka ]
```

---

## 📂 3. Struktur Direktori Proyek (Sesuai Slide 36)

```text
laravel dwi/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php         <- Otentikasi sesi: Login, Logout, Session Regenerate
│   │   ├── DashboardController.php    <- 5 Widget ringkasan, agregat kategori & peringatan stok
│   │   ├── ProductController.php      <- Resource Controller CRUD (index, create, store, show, edit, update, destroy)
│   │   ├── CategoryController.php     <- Master Kategori & validasi integritas
│   │   ├── SupplierController.php     <- Master Supplier & relasi Many-to-Many
│   │   ├── StockController.php        <- Transaksi Barang Masuk & Keluar (DB::transaction & lockForUpdate)
│   │   └── UserController.php         <- Administrasi Pengguna (Admin & Staff)
│   └── Models/
│       ├── User.php                   <- Akun pengguna & role check (isAdmin)
│       ├── Category.php               <- Relasi: hasMany(Product::class)
│       ├── Product.php                <- Relasi: belongsTo(Category), belongsToMany(Supplier), hasMany(StockMovement)
│       ├── Supplier.php               <- Relasi: belongsToMany(Product)
│       └── StockMovement.php          <- Relasi: belongsTo(Product), belongsTo(User)
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php            <- Tabel users (username, role admin/staff)
│   │   ├── ..._create_categories_table.php       <- Tabel categories
│   │   ├── ..._create_products_table.php         <- Tabel products (FK category_id restrictOnDelete)
│   │   ├── ..._create_suppliers_table.php        <- Tabel suppliers
│   │   ├── ..._create_product_suppliers_table.php<- Junction table relasi Many-to-Many
│   │   └── ..._create_stock_movements_table.php  <- Audit trail histori stok masuk/keluar
│   ├── factories/
│   │   ├── CategoryFactory.php
│   │   └── ProductFactory.php                    <- Factory data dummy uji (Slide 29)
│   └── seeders/
│       └── DatabaseSeeder.php                    <- Pengisi data demo akun & transaksi awal (Slide 30)
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php              <- Master layout Bootstrap 5 (Persis P4: Navbar gelap, ikon, footer)
│   ├── components/
│   │   └── alert.blade.php            <- Blade Component <x-alert> (Slide 23)
│   ├── auth/
│   │   └── login.blade.php            <- Form login rapi dengan proteksi CSRF
│   ├── dashboard.blade.php            <- 5 Kartu statistik & tabel laporan agregat
│   ├── products/                      <- Modul CRUD Produk (index, create, edit, show)
│   ├── categories/                    <- Modul CRUD Master Kategori
│   ├── suppliers/                     <- Modul CRUD Master Supplier
│   ├── stock/                         <- Modul Transaksi (in, out, history)
│   └── users/                         <- Modul Administrasi User (Admin only)
├── routes/
│   └── web.php                        <- Resource routing, grouping, & middleware auth
└── .env                               <- Konfigurasi lingkungan & database MySQL
```

---

## ⚡ 4. Fitur-Fitur Lengkap (Persis Seperti P4)

1. **Autentikasi Multi-User (Admin & Staff):**
   - Login menggunakan akun **Username & Password**.
   - Otentikasi aman melalui `Auth::attempt` dan pembaruan session ID (`session()->regenerate()`).
   - Penegakan role: Admin dapat mengelola user & seluruh master data, sedangkan Staff dibatasi hanya untuk transaksi dan melihat katalog.

2. **Dashboard & Laporan Agregat (Slide 14, 28, 29):**
   - **5 Widget Metrik:** Total Produk (`Product::count()`), Nilai Total Inventory (`SUM(stock * price)`), Produk Stok Rendah, Total Barang Masuk, dan Total Barang Keluar.
   - Peringatan banner merah jika ada produk dengan `stock <= minimum_stock`.
   - Ringkasan stok per kategori menggunakan agregat Eloquent (`withCount`, `withSum`, `withAvg`).
   - Laporan arus masuk vs keluar per produk.

3. **Master Produk (CRUD Lengkap):**
   - **Pencarian (Search):** Berdasarkan nama produk atau kode SKU.
   - **Penyaringan (Filter):** Berdasarkan kategori aktif.
   - **Pengurutan (Sorting):** Berdasarkan Nama, Harga, atau Stok (ASC/DESC).
   - **Pagination:** Paginasi dinamis dengan mempertahankan query filter (`withQueryString()`).
   - **Relasi Many-to-Many:** Dapat memilih banyak supplier pemasok sekaligus melalui junction table `product_suppliers`.

4. **Business Logic & Transaksi Stok (Slide 25–27):**
   - **Barang Masuk (IN):** Penambahan stok dibungkus dalam `DB::transaction()`.
   - **Barang Keluar (OUT):** Menggunakan locking `Product::lockForUpdate()`, validasi semantic stok mencukupi (*anti-stok negatif*), dan pencatatan audit trail ke `stock_movements`.
   - **Proteksi Hapus Data:** Produk yang memiliki histori transaksi tidak dapat dihapus sembarangan.

---

## 🛠️ 5. Cara Menjalankan Aplikasi di Komputer Anda

### Langkah 1: Nyalakan MySQL di XAMPP
Buka **XAMPP Control Panel**, lalu pastikan modul **MySQL** dan **Apache** dalam keadaan aktif (**Running**).

### Langkah 2: Buka Terminal / Command Prompt
Buka Command Prompt di dalam folder proyek:
```cmd
cd "C:\xampp\htdocs\laravel dwi"
```

### Langkah 3: Migrasi & Isi Data Awal (Satu Perintah Saja)
Sesuai **Slide 27 & 30**, jalankan perintah:
```cmd
php artisan migrate:fresh --seed
```
*Perintah ini akan otomatis membuat seluruh tabel relasional, akun user, kategori, supplier, produk, dan transaksi demo awal.*

### Langkah 4: Nyalakan Server Laravel
```cmd
php artisan serve
```

### Langkah 5: Buka di Browser
Buka browser dan akses alamat:
👉 **`http://127.0.0.1:8000/login`**

---

## 🔑 6. Akun Login untuk Praktikum & Demo Dosen

| Role | Username | Password | Hak Akses Fitur |
|---|---|---|---|
| **Administrator** | `admin` | `admin123` | Akses penuh seluruh menu: Dashboard, Produk (CRUD), Kategori, Supplier, Transaksi Stok, dan Kelola Pengguna. |
| **Staff** | `staff` | `staff123` | Akses terbatas: Melihat produk, mencatat barang masuk/keluar, dan melihat histori transaksi. Menu Kelola User otomatis disembunyikan. |

---

## 📋 7. Checklist Kelulusan Pertemuan 5 (Sesuai Slide 46)

- [x] Project Laravel dapat dijalankan (`php artisan serve`).
- [x] `.env` dan koneksi database benar (`DB_DATABASE=inventory_db`).
- [x] `route:list` menampilkan route resource yang diharapkan.
- [x] Migration dapat dijalankan dari database kosong (`migrate:fresh`).
- [x] Seeder menghasilkan data uji (`DatabaseSeeder`).
- [x] Model memiliki relationship yang benar (`belongsTo`, `hasMany`, `belongsToMany`).
- [x] Resource controller menangani CRUD (`ProductController`).
- [x] Blade memakai layout master dan output escaping aman.
- [x] Form memiliki `@csrf`, validasi, dan pesan error `@error`.
- [x] Search dan pagination mempertahankan query string (`withQueryString()`).
- [x] Inventory Management System P4 berhasil dipindahkan secara utuh dan konsisten.
