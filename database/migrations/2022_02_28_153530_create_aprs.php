<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAprs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aprs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('service_id');
            $table->text('maker');
            $table->longText('observation')->nullable();
            $table->boolean('fl_status')->default(0);
            $table->boolean('fl_deleted')->default(0);
            $table->timestamps();
        });
        Schema::create('apr_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('apr_id');
            $table->text('activity');
            $table->text('risk_source');
            $table->text('risk_factor');
            $table->text('consequence');
            $table->text('action');
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
        Schema::dropIfExists('apr_items');
        Schema::dropIfExists('aprs');
    }
}
