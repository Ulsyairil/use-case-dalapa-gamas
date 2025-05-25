<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWoVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wo_verification', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workorder_id');
            $table->string('status'); // Disetujui, Ditolak
            $table->text('note')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::table('wo_verification', function (Blueprint $table) {
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
        Schema::dropIfExists('wo_verification');
    }
}
