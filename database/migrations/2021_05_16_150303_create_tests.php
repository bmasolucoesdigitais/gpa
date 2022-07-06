<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')
            ->on('companies')->onDelete('cascade');
            $table->char('name', 150);
            $table->integer('questions');
            $table->integer('minutes');
            $table->boolean('fl_deleted')->default(0);
            $table->timestamps();

        });
        Schema::create('test_execution', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('test_id')->unsigned()->nullable();
            $table->foreign('test_id')->references('id')
            ->on('tests')->onDelete('cascade');
            $table->text('questions');
            $table->text('answears');
            $table->datetime('dt_ini');
            $table->boolean('fl_deleted')->default(0);
            $table->timestamps();

        });
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('test_id')->unsigned()->nullable();
            $table->foreign('test_id')->references('id')
            ->on('tests')->onDelete('cascade');
            $table->text('question')->nullable();
            $table->text('answer1')->nullable();
            $table->text('answer2')->nullable();
            $table->text('answer3')->nullable();
            $table->text('answer4')->nullable();
            $table->text('answer5')->nullable();
            $table->text('answer6')->nullable();
            $table->text('answer7')->nullable();
            $table->text('answer8')->nullable();
            $table->text('answer9')->nullable();
            $table->text('answer10')->nullable();
            $table->integer('correct_answer');
            $table->boolean('fl_deleted')->default(0);
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
        Schema::dropIfExists('tests');
        Schema::dropIfExists('test_execution');
        Schema::dropIfExists('questions');
    }
}
