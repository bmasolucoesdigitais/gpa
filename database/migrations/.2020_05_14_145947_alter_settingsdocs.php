<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSettingsdocs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       /*  Schema::table('settingsdocs', function (Blueprint $table) {
            $table->text('aditional_client')->nullable();
            $table->text('aditional_provider')->nullable();
            $table->text('aditional_abaco')->nullable();
        }); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       /*  Schema::table('settingsdocs', function (Blueprint $table) {
            $table->dropColumn(['aditional_client']);
            $table->dropColumn(['aditional_provider']);
            $table->dropColumn(['aditional_abaco']);
        }); */
    }
}
