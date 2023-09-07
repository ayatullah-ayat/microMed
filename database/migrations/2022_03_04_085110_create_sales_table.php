<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->date('sales_date');
            $table->string('invoice_no');
            $table->text('sold_sizes')->nullable();
            $table->text('sold_colors')->nullable();
            $table->unsignedBigInteger('sold_total_qty')->default(0)->comment('grand_qty');
            $table->float('sold_total_price', 10, 3)->default(0)->comment('grand_total');
            $table->float('total_discount_price', 10, 3)->default(0);
            $table->float('order_grand_total', 10, 3)->default(0);
            $table->text('sales_note')->nullable();
            $table->integer('number_of_returned')->unsigned()->default(0);
            $table->unsignedBigInteger('sold_by')->nullable();
            $table->string('sold_by_name')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->float('total_payment', 10, 3)->default(0);
            $table->float('total_payment_due', 10, 3)->default(0);
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
        Schema::dropIfExists('sales');
    }
}
