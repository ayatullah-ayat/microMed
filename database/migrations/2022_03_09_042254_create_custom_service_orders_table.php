<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomServiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_service_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->unsignedBigInteger('custom_service_product_id')->nullable();
            $table->string('custom_service_product_name')->nullable();
            $table->string('order_no')->unique();
            $table->float('order_qty',10,3)->default(0);// it will be added in further if client needs
            $table->float('order_discount_price',10,3)->default(0);// it will be added in further if client needs
            $table->float('order_total_price',10,3)->default(0);// it will be added in further if client needs
            $table->text('order_attachment')->nullable();
            $table->text('note')->nullable();
            $table->float('advance_balance', 10,3)->default(0);

            $table->enum('status', ['pending', 'confirm', 'processing', 'completed', 'cancelled', 'returned'])->default('pending');

            $table->unsignedBigInteger('delivered_qty')->default(0);
            $table->float('delivered_price', 10, 3)->default(0);
            $table->timestamp('delivered_at')->nullable();

            $table->unsignedBigInteger('delivered_by')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
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
        Schema::dropIfExists('custom_service_orders');
    }
}
