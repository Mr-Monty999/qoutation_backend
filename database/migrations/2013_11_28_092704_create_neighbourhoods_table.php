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
        Schema::create('neighbourhoods', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("city_id");
            $table->boolean("status")->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("city_id")->references("id")->on("cities")->cascadeOnDelete()->cascadeOnUpdate();
        });

        $neighbourhoods = database_path("saudi-gis/neighbourhoods.sql");
        $neighbourhoods = file_get_contents($neighbourhoods);
        DB::unprepared($neighbourhoods);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('neighbourhoods');
    }
};
