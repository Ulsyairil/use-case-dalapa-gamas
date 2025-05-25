<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTroubleTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trouble_ticket', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ticket_number');
            $table->string('headline');
            $table->text('description');
            $table->string('status'); // Open, In Progress, Closed
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('workorder', function (Blueprint $table) {
            $table->foreign('ticket_id')->references('id')->on('trouble_ticket')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trouble_ticket');
    }
}
