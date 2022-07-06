<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyDocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('company_document', function(Blueprint $table){ 
         $table->integer('document_id')->unsigned()->nullable();
         $table->foreign('document_id')->references('id')
         ->on('documents')->onDelete('cascade');

         $table->integer('company_id')->unsigned()->nullable();
         $table->foreign('company_id')->references('id')
         ->on('companies')->onDelete('cascade');

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
        Schema::drop('company_document');
    }
}
