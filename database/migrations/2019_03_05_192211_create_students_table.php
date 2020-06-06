<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->index();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->integer('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('id')->on('master_departments');
            $table->string('nim')->nullable()->unique()->index();
            $table->string('name');
            $table->integer('trial_teacher_id')->unsigned();
            $table->foreign('trial_teacher_id')->references('id')->on('staff');
            $table->integer('teacher_id')->nullable()->unsigned()->index();
            $table->foreign('teacher_id')->references('id')->on('staff');
            $table->integer('media_source_id')->nullable()->unsigned();
            $table->foreign('media_source_id')->references('id')->on('master_media_sources');
            $table->integer('class_id')->nullable()->unsigned();
            $table->foreign('class_id')->references('id')->on('master_classes');
            $table->integer('grade_id')->nullable()->unsigned();
            $table->foreign('grade_id')->references('id')->on('master_grades');
            $table->integer('phase_id')->nullable()->unsigned();
            $table->foreign('phase_id')->references('id')->on('master_student_phases');
            $table->string('parent_name');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('note')->nullable();
            $table->date('birth_date');

            /* Column for student out */
            $table->date('out_date')->nullable();
            $table->integer('out_reason_id')->nullable()->unsigned();
            $table->foreign('out_reason_id')->references('id')->on('master_student_out_reasons');
            /* End column for trial */

            $table->integer('note_id')->nullable()->unsigned();
            $table->foreign('note_id')->references('id')->on('master_student_notes');
            $table->date('joined_date');
            $table->enum('status', ['Trial', 'Active', 'Out'])->default('Active');
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
        Schema::dropIfExists('students');
        
            $table->string('note')->nullable();
    }
}
