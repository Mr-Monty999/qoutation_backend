<?php

use App\Models\Activity;
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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("image")->nullable();
            $table->text("description")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Activity::create([
            "name" => "المقاولات"
        ]);
        Activity::create([
            "name" => "التجارة"
        ]);
        Activity::create([
            "name" => "السياحة"
        ]);
        Activity::create([
            "name" => "التكنولوجيا"
        ]);
        Activity::create([
            "name" => "التعليم"
        ]);
        Activity::create([
            "name" => "الصحة"
        ]);
        Activity::create([
            "name" => "الطاقة"
        ]);
        Activity::create([
            "name" => "الرياضة"
        ]);
        Activity::create([
            "name" => "الترفيه"
        ]);
        Activity::create([
            "name" => "الملابس"
        ]);
        Activity::create([
            "name" => "المواد الغذائية"
        ]);
        Activity::create([
            "name" => "التجميل"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
