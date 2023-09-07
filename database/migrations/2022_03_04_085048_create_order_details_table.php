<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('order_no')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->text('product_name')->nullable();
            $table->string('product_color')->nullable();
            $table->string('product_size')->nullable();
            $table->unsignedBigInteger('product_qty')->default(0);
            $table->float('purchase_price', 10, 3)->default(0);
            $table->float('product_price', 10, 3)->default(0);
            $table->float('discount_price', 10, 3)->default(0);
            $table->float('wholesale_price', 10, 3)->default(0);
            $table->float('subtotal', 10, 3)->default(0);
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
        Schema::dropIfExists('order_details');
    }
}
