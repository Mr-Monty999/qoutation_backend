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
        Schema::create('user_otps', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger("user_id");
            $table->string("identifier");
            $table->string("code");
            // $table->string("type");
            $table->timestamp("verified_at")->nullable();
            $table->timestamp("expired_at")->nullable();
            $table->timestamps();
            $table->softDeletes();




            // $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_otps');
    }
};
