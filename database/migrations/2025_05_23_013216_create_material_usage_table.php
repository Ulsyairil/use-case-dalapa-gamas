<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialUsageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_usage', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workorder_id');
            $table->uuid('material_id');
            $table->bigInteger('quantity')->default(0);
            $table->bigInteger('price')->default(0);
            $table->bigInteger('total_price')->default(0);
            $table->text('note')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('material_usage', function (Blueprint $table) {
            $table->foreign('workorder_id')->references('id')->on('workorder')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('material_id')->references('id')->on('material')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_usage');
    }
}
