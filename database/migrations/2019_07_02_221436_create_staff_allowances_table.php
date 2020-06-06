<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_allowances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('income_id')->unsigned()->index();
            $table->foreign('income_id')->references('id')->on('staff_incomes');
            $table->integer('allowance_group_id')->unsigned()->index();
            $table->foreign('allowance_group_id')->references('id')->on('master_special_allowance_groups');
            $table->decimal('amount', 18, 2);
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
        Schema::dropIfExists('staff_allowances');
    }
}
