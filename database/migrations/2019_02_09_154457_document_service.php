<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DocumentService extends Migration
{
       /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_service', function(Blueprint $table){ 
         $table->integer('document_id')->unsigned()->nullable();
         $table->foreign('document_id')->references('id')
         ->on('documents')->onDelete('cascade');

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
        Schema::drop('document_service');
    }
}
