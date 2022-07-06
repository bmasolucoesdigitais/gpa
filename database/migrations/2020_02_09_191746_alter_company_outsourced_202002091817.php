<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyOutsourced202002091817 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_outsourced', function (Blueprint $table) {
          $table->boolean('fl_ready')->default(0);
          $table->date('dt_ready_sent')->nullable();
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_outsourced', function (Blueprint $table) {
        $table->dropColumn(['fl_ready']);
        $table->dropColumn(['dt_ready_sent']);
    });
    }
}
