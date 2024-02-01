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
        Schema::create('product_quotations', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger("service_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("service_product_id")->nullable();
            $table->decimal("price", 20, 2);
            $table->string("description")->nullable();
            $table->timestamps();
            $table->softDeletes();


            // $table->foreign("service_id")->references("id")->on("services")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("service_product_id")->references("id")->on("service_products")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('product_quotations');
    }
};
