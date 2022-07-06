<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceschedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serviceschedules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('store_id');
            $table->foreign('store_id')->references('id')->on('companies')->onDelete('cascade');
            $table->date('date_ini');
            $table->date('date_end');
            $table->text('service');
            $table->boolean('aproved')->default(0);
            $table->boolean('clientaproved')->default(0);
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('file_id')->nullable();
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->boolean('fl_deleted')->default(0);
            $table->timestamps();
        });

        Schema::create('employee_serviceschedule', function(Blueprint $table){
            $table->integer('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')
            ->on('employees')->onDelete('cascade');
   
            $table->integer('serviceschedule_id')->unsigned()->nullable();
            $table->foreign('serviceschedule_id')->references('id')
            ->on('serviceschedules')->onDelete('cascade');
   
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
        Schema::dropIfExists('employee_serviceschedule');
        Schema::dropIfExists('serviceschedules');
    }
}
