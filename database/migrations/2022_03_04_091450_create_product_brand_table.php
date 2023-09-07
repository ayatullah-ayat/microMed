<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//https://laraveldaily.com/pivot-tables-and-many-to-many-relationships/
class CreateProductBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_brand', function (Blueprint $table) {
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('brand_id')->unsigned();
            $table->string('brand_name')->nullable();
            $table->primary(['product_id', 'brand_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_brand');
    }
}
