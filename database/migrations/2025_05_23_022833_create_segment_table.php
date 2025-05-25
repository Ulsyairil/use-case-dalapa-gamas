<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('segment', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workorder_id');
            $table->string('segment'); // Feeder / Distribusi
            $table->text('address');
            $table->string('coordinates');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('segment', function (Blueprint $table) {
            $table->foreign('workorder_id')->references('id')->on('workorder')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('segment');
    }
}
