<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTrainingschedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainingschedules', function (Blueprint $table) {
            $table->text('url')->nullable();
            $table->unsignedInteger('test_id')->nullable();
            $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainingschedules', function (Blueprint $table) {
            $table->dropColumn(['url']);
            $table->dropForeign(['test_id']);
            $table->dropColumn(['test_id']);
        });
    }
}
