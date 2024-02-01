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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("wallet_id");
            $table->string("type")->nullable();
            $table->decimal("amount", 20)->default(0);
            $table->string("status")->default("pending");
            $table->string("number")->unique();
            $table->string("uuid")->unique();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("wallet_id")->references("id")->on("wallets")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
