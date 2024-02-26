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
        Schema::table('quotation_replies', function (Blueprint $table) {
            $table->unsignedBigInteger("accepted_by")->nullable()->after("user_id");
            $table->string("title")->nullable()->after("price");
            $table->renameColumn("price", "unit_price");


            $table->foreign("accepted_by")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotation_replies', function (Blueprint $table) {
            $table->dropConstrainedForeignId("accepted_by");
            $table->dropColumn("title");
            $table->renameColumn("unit_price", "price");
        });
    }
};
