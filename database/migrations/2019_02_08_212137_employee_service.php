<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployeeService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_service', function(Blueprint $table){ 
         $table->integer('employee_id')->unsigned()->nullable();
         $table->foreign('employee_id')->references('id')
         ->on('employees')->onDelete('cascade');

         $table->integer('service_id')->unsigned()->nullable();
         $table->foreign('service_id')->references('id')
         ->on('services')->onDelete('cascade');

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
        Schema::drop('employee_service');
    }
}
