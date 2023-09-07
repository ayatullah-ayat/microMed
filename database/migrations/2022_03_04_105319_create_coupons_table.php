<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('discount_type');
            $table->enum('coupon_type',['include','exclude','category','user',null])->nullable();
            $table->string('coupon_name');
            $table->string('coupon_code')->unique();
            $table->unsignedBigInteger('coupon_discount');
            $table->date('coupon_validity');
            $table->unsignedBigInteger('usage_limit')->nullable();
            $table->unsignedBigInteger('min_bill_limit')->nullable();
            $table->unsignedBigInteger('max_bill_limit')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('coupons');
    }
}
