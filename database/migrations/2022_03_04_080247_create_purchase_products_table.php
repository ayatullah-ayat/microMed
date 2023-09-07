<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('invoice_no')->nullable();
            $table->text('product_name')->nullable();
            $table->string('product_unit')->nullable();
            $table->text('product_colors')->nullable();
            $table->text('product_sizes')->nullable();
            $table->unsignedBigInteger('product_qty')->default(0);
            $table->unsignedBigInteger('stocked_qty')->default(0);
            $table->unsignedBigInteger('returned_qty')->default(0);
            $table->float('product_price',10,3)->default(0);
            $table->float('subtotal',10,3)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_products');
    }
}
