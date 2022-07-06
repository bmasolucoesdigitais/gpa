<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailogCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailogs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('mails')->nullable();
            $table->text('subject')->nullable();
            $table->longText('message', 100)->nullable();
            $table->boolean('status')->default(0);
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
        //$table->dropForeign(['company_id']);
        Schema::dropIfExists('mailogs');
    }
}
