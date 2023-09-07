<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOurCustomServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('our_custom_services', function (Blueprint $table) {
            $table->id();
            $table->string('service_name')->unique();
            $table->text('service_description')->nullable();
            $table->text('service_thumbnail')->nullable();
            $table->boolean('is_allow_caption')->default(1);
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
        Schema::dropIfExists('our_custom_services');
    }
}
