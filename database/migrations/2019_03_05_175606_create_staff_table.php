<?php

use App\Enum\Staff\StaffStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->index();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->string('nik')->unique()->index();
            $table->string('name');
            $table->integer('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('id')->on('master_departments');
            $table->integer('position_id')->unsigned()->index();
            $table->foreign('position_id')->references('id')->on('master_staff_positions');

            $table->date('birth_date');
            $table->string('phone');
            $table->string('email');

            $table->string('account_bank');
            $table->string('account_number');
            $table->string('account_name');

            $table->date('joined_date');
            $table->enum('status', StaffStatus::keys())->default('Active');
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
        Schema::dropIfExists('staff');
    }
}
