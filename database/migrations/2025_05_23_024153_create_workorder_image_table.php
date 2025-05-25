<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkorderImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workorder_image', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workorder_id');
            $table->uuid('image_id');
            $table->timestamps();
        });

        Schema::table('workorder_image', function (Blueprint $table) {
            $table->foreign('workorder_id')->references('id')->on('workorder')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('workorder_image');
    }
}
