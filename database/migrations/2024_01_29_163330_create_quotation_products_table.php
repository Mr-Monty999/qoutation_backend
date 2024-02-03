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
        Schema::create('quotation_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("quotation_id");
            $table->unsignedBigInteger("product_id")->nullable();
            $table->string("name");
            $table->integer("quantity");
            $table->string("description")->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign("quotation_id")->references("id")->on("quotations")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("product_id")->references("id")->on("products")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_products');
    }
};
