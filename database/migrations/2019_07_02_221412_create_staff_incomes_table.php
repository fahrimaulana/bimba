<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->index();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->integer('staff_id')->unsigned()->index();
            $table->foreign('staff_id')->references('id')->on('staff');
            $table->decimal('basic_salary', 18, 2)->default(0);
            $table->decimal('daily', 18, 2)->default(0);
            $table->decimal('functional', 18, 2)->default(0);
            $table->decimal('health', 18, 2)->default(0);
            $table->decimal('underpayment', 18, 2)->default(0);
            $table->integer('underpayment_month')->nullable();
            $table->decimal('other', 18, 2)->default(0);
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
        Schema::dropIfExists('staff_incomes');
    }
}
