<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmployeeTraining20210715 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_training', function (Blueprint $table) {
                $table->integer('fl_present')->default(0);
                $table->integer('status_test')->default(1);
                $table->longText('answers_json')->nullable();
                $table->text('token')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_training', function (Blueprint $table) {
            $table->dropColumn(['fl_present']);
            $table->dropColumn(['status_test']);
            $table->dropColumn(['answers_json']);
            $table->dropColumn(['token']);
        });
    }
}
