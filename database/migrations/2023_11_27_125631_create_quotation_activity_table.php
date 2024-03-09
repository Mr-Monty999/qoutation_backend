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
        Schema::create('quotation_activity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("quotation_id");
            $table->unsignedBigInteger("activity_id");
            $table->timestamps();

            $table->foreign("quotation_id")->references("id")->on("quotations")->cascadeOnDelete()->cascadeOnUpdate();;
            $table->foreign("activity_id")->references("id")->on("activities")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_activity');
    }
};
