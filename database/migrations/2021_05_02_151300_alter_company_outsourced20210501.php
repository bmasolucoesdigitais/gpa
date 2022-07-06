<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyOutsourced20210501 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_outsourced', function (Blueprint $table) {
          $table->boolean('fl_active')->default(1);
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
        $table->dropColumn(['fl_active']);
    });
    }
}
