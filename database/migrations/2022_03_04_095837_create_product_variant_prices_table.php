<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variant_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();
            $table->string('color_name');
            $table->string('size_name');
            $table->float('unit_price', 10, 3)->unsigned()->default(0);
            $table->float('sales_price', 10 ,3)->unsigned()->default(0);
            $table->float('wholesale_price', 10 ,3)->unsigned()->default(0);
            $table->unsignedBigInteger('product_qty')->default(0);
            $table->unsignedBigInteger('stock_qty')->default(0);
            $table->unsignedBigInteger('stock_out_qty')->default(0);
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
        Schema::dropIfExists('product_variant_prices');
    }
}
