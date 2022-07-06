<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_employee', function(Blueprint $table){ 
         $table->integer('company_id')->unsigned()->nullable();
         $table->foreign('company_id')->references('id')
         ->on('companies')->onDelete('cascade');

         $table->integer('employee_id')->unsigned()->nullable();
         $table->foreign('employee_id')->references('id')
         ->on('employees')->onDelete('cascade');

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
        Schema::dropIfExists('company_employee');
    }
}
