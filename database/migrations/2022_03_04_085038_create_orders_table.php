<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('payment_type')->nullable();

            $table->date('order_date');
            $table->string('order_no');
            $table->string('coupon_code')->nullable();
            $table->text('order_sizes')->nullable();
            $table->text('order_colors')->nullable();

            $table->text('shipping_address')->nullable();
            $table->float('service_charge', 10,3)->default(0);
            $table->float('shipment_cost',10,3)->default(0);

            $table->float('discount_price',10,3)->default(0);

            $table->enum('status', ['pending', 'confirm', 'processing', 'completed', 'cancelled', 'returned'])->default('pending');

            $table->unsignedBigInteger('order_total_qty')->default(0)->comment('grand_qty');
            $table->float('order_total_price', 10, 3)->default(0)->comment('grand_total');
            $table->float('payment_total_price', 10, 3)->default(0)->comment('summary of total payment');

            $table->text('order_note')->nullable();

            $table->boolean('is_send_mail')->default(0);
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
        Schema::dropIfExists('orders');
    }
}