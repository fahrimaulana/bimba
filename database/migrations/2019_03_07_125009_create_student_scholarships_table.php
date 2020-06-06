<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_scholarships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned()->index();
            $table->foreign('student_id')->references('id')->on('students');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('period');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('students', function(Blueprint $table) {
            $table->integer('active_scholarship_id')->after('department_id')->nullable()->unsigned();
            $table->foreign('active_scholarship_id')->references('id')->on('student_scholarships');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_scholarships');
    }
}
