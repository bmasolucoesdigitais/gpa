<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsdocs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('settingsdocs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('company_id')->default(0);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedInteger('document_id')->default(0);
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->boolean('fl_client')->default(0);
            $table->boolean('fl_provider')->default(0);
            $table->boolean('fl_abaco')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settingsdoc');
    }
}
