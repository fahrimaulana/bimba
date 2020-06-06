<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->index();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->string('code');
            $table->date('date');
            $table->decimal('value', 18, 2);
            $table->integer('student_id')->unsigned()->index();
            $table->foreign('student_id')->references('id')->on('students');
            $table->integer('invited_student_id')->unsigned()->index();
            $table->foreign('invited_student_id')->references('id')->on('students');
            $table->enum('status', ['Penyerahan', 'Pemakaian'])->default('Penyerahan');
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
        Schema::dropIfExists('vouchers');
    }
}
