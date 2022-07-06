<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveredsTable20191005 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivereds', function (Blueprint $table) {
          $table->unsignedInteger('company_id')->nullable()->change();
          $table->unsignedInteger('employee_id')->nullable()->change();
      });    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
