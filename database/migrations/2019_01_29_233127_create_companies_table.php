<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name', 100);
            $table->char('cep', 9)->default('00000-000');
            $table->char('address', 100);
            $table->integer('number');
            $table->char('neighborhood', 100);
            $table->char('citie', 100);
            $table->char('state', 2);
            $table->char('country', 50);
            $table->char('cnpj', 18);
            $table->boolean('fl_aprove')->default(1);
            $table->boolean('fl_active')->default(1);
            $table->boolean('fl_billing')->default(1);
            $table->unsignedInteger('company_id')->default(0);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->boolean('fl_client')->default(0);
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
        Schema::dropIfExists('companies');
    }
}
