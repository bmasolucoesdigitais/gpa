<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name', 100);
            $table->char('description', 250);
            $table->boolean('fl_criteria')->default(0);
            $table->unsignedInteger('company_id')->default(0);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->boolean('fl_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //$table->dropForeign(['company_id']);
        Schema::dropIfExists('documents');
    }
}
