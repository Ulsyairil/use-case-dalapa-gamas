<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workorder', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('technician_id');
            $table->uuid('ticket_id');
            $table->string('status'); // Draft, Selesai, Diverifikasi, Revisi
            $table->string('note')->nullable();
            $table->boolean('is_submitted')->default(0);
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('workorder', function (Blueprint $table) {
            $table->foreign('technician_id')->references('id')->on('technician')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workorder');
    }
}
