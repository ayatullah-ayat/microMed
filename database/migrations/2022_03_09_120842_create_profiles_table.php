<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('photo')->nullable();
            $table->string('mobile_no')->nullable()->unique();
            $table->string('country')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->enum('gender',[null,1,2,3])->default(null)->comment('1-male, 2-female, 3-others');
            $table->text('address')->nullable();
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
        Schema::dropIfExists('profiles');
    }
}
