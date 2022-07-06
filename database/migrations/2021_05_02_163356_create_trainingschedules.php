<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingschedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainingschedules', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name', 150);
            $table->timestamps();
            $table->unsignedInteger('company_id')->default(0);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->datetime('dt_ini');
            $table->datetime('dt_end');
            $table->integer('vacancies');
            $table->boolean('fl_deleted')->default(0);
            $table->boolean('fl_accomplished')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainingschedules');
    }
}
