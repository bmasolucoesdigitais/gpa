<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterScheduledservice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('serviceschedules', function (Blueprint $table) {
            $table->boolean('techaproved')->default(0);
            $table->longtext('observation');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('serviceschedules', function (Blueprint $table) {
            $table->dropColumn('techaproved');
            $table->dropColumn('observation');
        });
    }
}
