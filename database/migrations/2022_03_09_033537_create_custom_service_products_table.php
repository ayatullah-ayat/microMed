<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomServiceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_service_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('category_name')->nullable();

            $table->string('product_name')->unique();
            $table->text('product_description')->nullable();
            $table->text('product_thumbnail')->nullable();
            $table->float('product_price',10,3)->default(0)->comment('optional');// we will add the price if client neeeds in future
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('custom_service_products');
    }
}
