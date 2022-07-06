<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_employee', function (Blueprint $table) {

         $table->integer('document_id')->unsigned()->nullable();
         $table->foreign('document_id')->references('id')
         ->on('documents')->onDelete('cascade');

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
        Schema::dropIfExists('document_employee');
    }
}
