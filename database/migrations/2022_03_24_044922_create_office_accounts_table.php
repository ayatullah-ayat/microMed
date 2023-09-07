<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_accounts', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('account_type')->nullable();
            $table->text('description')->nullable();
            $table->float('cash_in', 10, 3)->default(0);
            $table->float('cash_out',10, 3)->default(0);
            $table->float('current_balance',10, 3)->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->string('updated_by_name')->nullable();
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
        Schema::dropIfExists('office_accounts');
    }
}
