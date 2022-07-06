<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_client', function (Blueprint $table) {
            $table->text('mail_company')->nullable();
            $table->text('mail_client')->nullable();
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_client', function (Blueprint $table) {
            $table->dropColumn(['mail_company']);
            $table->dropColumn(['mail_client']);
    });
    }
}
