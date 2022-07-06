<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name', 100);
            $table->char('cpf', 14);
            $table->char('rg');
            $table->date('borndate');
            $table->boolean('allowed');
           // $table->unsignedInteger('service_id');
            //$table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            //$table->unsignedInteger('company_id');
            //$table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
        //$table->dropForeign(['company_id', 'service_id']);
        Schema::dropIfExists('employees');

    }
}
