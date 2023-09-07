<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */ 
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('purchase_product_id')->nullable();
            $table->string('invoice_no')->nullable();
            $table->text('barcode')->nullable();
            $table->text('product_name')->nullable();
            $table->string('product_unit')->nullable();
            $table->string('product_color')->nullable();
            $table->string('product_size')->nullable();
            $table->unsignedBigInteger('returned_qty')->default(0);
            $table->float('purchase_price', 10, 3)->default(0);
            $table->float('unit_price', 10, 3)->default(0);
            $table->float('sales_price', 10, 3)->default(0);
            $table->float('wholesale_price', 10, 3)->default(0);
            $table->float('subtotal', 10, 3)->default(0);
            $table->unsignedBigInteger('returned_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('purchase_returns');
    }
}
