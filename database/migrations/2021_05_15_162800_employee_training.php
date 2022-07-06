<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployeeTraining extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('employee_training', function(Blueprint $table){
         $table->integer('employee_id')->unsigned()->nullable();
         $table->foreign('employee_id')->references('id')
         ->on('employees')->onDelete('cascade');

         $table->integer('trainingschedule_id')->unsigned()->nullable();
         $table->foreign('trainingschedule_id')->references('id')
         ->on('trainingschedules')->onDelete('cascade');

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
        Schema::drop('employee_training');
    }
}
