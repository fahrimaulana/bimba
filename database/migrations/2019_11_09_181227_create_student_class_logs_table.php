<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentClassLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_class_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->index();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->integer('student_id')->unsigned()->index();
            $table->foreign('student_id')->references('id')->on('students');
            $table->integer('old_class_id')->nullable()->unsigned();
            $table->foreign('old_class_id')->references('id')->on('master_classes');
            $table->integer('new_class_id')->nullable()->unsigned();
            $table->foreign('new_class_id')->references('id')->on('master_classes');
            $table->integer('old_grade_id')->nullable()->unsigned();
            $table->foreign('old_grade_id')->references('id')->on('master_grades');
            $table->integer('new_grade_id')->nullable()->unsigned();
            $table->foreign('new_grade_id')->references('id')->on('master_grades');
            $table->string('note')->nullable();
            $table->integer('old_price');
            $table->integer('new_price');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_class_logs');
    }
}
