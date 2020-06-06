<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->date('dt')->primary();
            $table->smallInteger('y')->nullable();
            $table->tinyInteger('q')->nullable();
            $table->tinyInteger('m')->nullable();
            $table->tinyInteger('d')->nullable();
            $table->tinyInteger('dw')->nullable();
            $table->string('monthName', 9)->nullable();
            $table->string('dayName', 9)->nullable();
            $table->tinyInteger('w')->nullable();
            $table->boolean('isWeekday')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendars');
    }
}
