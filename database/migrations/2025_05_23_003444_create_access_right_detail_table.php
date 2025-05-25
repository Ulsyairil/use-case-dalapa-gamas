<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessRightDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_right_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('access_id');
            $table->string('access_code');
            $table->tinyInteger('create')->default(0);
            $table->tinyInteger('read')->default(0);
            $table->tinyInteger('update')->default(0);
            $table->tinyInteger('delete')->default(0);
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('access_right_detail', function (Blueprint $table) {
            $table->foreign('access_id')->references('id')->on('access_right')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_right_detail');
    }
}
