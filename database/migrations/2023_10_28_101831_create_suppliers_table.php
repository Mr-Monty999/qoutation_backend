<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string("image")->nullable();
            $table->string("commercial_record_number");
            $table->string("commercial_record_image")->nullable();
            $table->string("address")->nullable();
            $table->string("activity_description")->nullable();
            $table->string("lat")->nullable();
            $table->string("lng")->nullable();
            $table->date("birthdate")->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};
