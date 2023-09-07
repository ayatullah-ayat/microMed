<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('category_name')->nullable();
            $table->string('moible_no')->nullable();

            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->date('order_date')->nullable();
            $table->text('order_sizes')->nullable(); // optional
            $table->text('order_colors')->nullable(); // optional
            $table->string('order_no')->unique();
            $table->float('order_qty', 10, 3)->default(0);
            $table->float('price', 10, 3)->default(0);
            $table->text('order_attachment')->nullable();
            $table->float('order_discount_price', 10, 3)->default(0);
            $table->float('total_order_price', 10, 3)->default(0);
            $table->float('advance_balance', 10, 3)->default(0);
            $table->float('due_price', 10, 3)->default(0);
            $table->float('service_charge', 10, 3)->default(0);
            $table->text('institute_description')->nullable();
            $table->text('address')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('other_orders');
    }
}
