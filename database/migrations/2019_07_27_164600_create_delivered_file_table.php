<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveredFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivered_file', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('delivered_id')->unsigned()->nullable();
            $table->foreign('delivered_id')->references('id')->on('delivereds')->onDelete('cascade');

            $table->integer('file_id')->unsigned()->nullable();
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');


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
        Schema::dropIfExists('delivered_file');
    }
}
