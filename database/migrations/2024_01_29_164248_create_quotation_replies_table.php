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
        Schema::create('quotation_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("quotation_invoice_id");
            $table->unsignedBigInteger("quotation_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("quotation_product_id")->nullable();
            $table->decimal("price", 20, 2);
            $table->string("description")->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign("quotation_invoice_id")->references("id")->on("quotation_invoices")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("quotation_id")->references("id")->on("quotations")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("quotation_product_id")->references("id")->on("quotation_products")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('quotation_replies');
    }
};
