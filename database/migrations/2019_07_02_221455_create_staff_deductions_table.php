<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_deductions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->index();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->integer('staff_id')->unsigned()->index();
            $table->foreign('staff_id')->references('id')->on('staff');
            $table->decimal('sick', 18, 2)->default(0);
            $table->decimal('leave', 18, 2)->default(0);
            $table->decimal('alpha', 18, 2)->default(0);
            $table->decimal('not_active', 18, 2)->default(0);
            $table->decimal('overpayment', 18, 2)->default(0);
            $table->integer('overpayment_month')->nullable();
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
        Schema::dropIfExists('staff_deductions');
    }
}
