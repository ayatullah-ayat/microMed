<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->unique()->nullable();
            $table->string('customer_phone')->unique()->nullable();
            $table->text('customer_address')->nullable();
            $table->text('institute_description')->nullable();
            $table->float('current_balance', 10,3)->default(0);
            
            $table->boolean('is_active')->default(1);
            $table->boolean('is_block')->default(0);
            
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
        Schema::dropIfExists('customers');
    }
}
