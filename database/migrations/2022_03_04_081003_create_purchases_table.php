<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('client_name')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('supplier_name')->nullable();
            $table->date('purchase_date');
            $table->string('currency')->nullable();
            $table->string('invoice_no');
            $table->text('sizes')->nullable();
            $table->text('colors')->nullable();
            $table->unsignedBigInteger('total_qty')->default(0)->comment('grand_qty');
            $table->float('total_price',10, 3)->default(0)->comment('grand_total');
            $table->float('total_payment',10,3)->default(0);
            $table->float('total_payment_due',10,3)->default(0);
            
            $table->text('purchase_note')->nullable();
            $table->boolean('is_manage_stock')->default(0);
            $table->boolean('is_returned')->default(0);
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
        Schema::dropIfExists('purchases');
    }
}
