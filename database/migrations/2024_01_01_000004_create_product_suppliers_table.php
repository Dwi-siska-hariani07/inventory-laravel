<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     * Junction table many-to-many products <-> suppliers
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_suppliers', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('supplier_price', 12, 2)->default(0.00);
            $table->timestamps();

            $table->primary(['product_id', 'supplier_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_suppliers');
    }
}
