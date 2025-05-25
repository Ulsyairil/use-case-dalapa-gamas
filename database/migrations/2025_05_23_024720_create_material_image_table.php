<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_image', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('material_id');
            $table->uuid('image_id');
            $table->timestamps();
        });

        Schema::table('material_image', function (Blueprint $table) {
            $table->foreign('material_id')->references('id')->on('material')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('image_id')->references('id')->on('image')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_image');
    }
}
