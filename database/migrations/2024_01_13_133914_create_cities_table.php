<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("parent_id")->nullable();
            $table->unsignedBigInteger("country_id");
            $table->boolean("status")->default(true);
            $table->softDeletes();
            $table->timestamps();


            $table->foreign("parent_id")->references("id")->on("cities")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("country_id")->references("id")->on("countries")->cascadeOnDelete()->cascadeOnUpdate();
        });

        $cities = database_path("saudi-gis/cities.sql");
        $cities = file_get_contents($cities);
        DB::unprepared($cities);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
};
