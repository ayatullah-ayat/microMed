<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();

            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();

            $table->string('payment_type')->nullable(); // bkash, cash, etc
            $table->string('transection_id')->nullable();
            $table->string('payer_id')->nullable();
            $table->string('currency')->nullable();
            $table->float('payment_amount')->nullable();
            $table->float('payment_due')->nullable();
            $table->text('payment_detail')->nullable();
            $table->string('payment_status')->nullable();
            $table->unsignedBigInteger('is_refunded')->default(0);
            $table->string('payment_by')->nullable();
            $table->string('refunded_by')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
