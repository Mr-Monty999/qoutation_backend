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
        Schema::create('quotation_quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("quotation_id");
            $table->unsignedBigInteger("user_id");
            $table->decimal("amount", 20, 2);
            $table->string("title")->nullable();
            $table->string("description")->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("quotation_id")->references("id")->on("quotations")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_quotations');
    }
};
