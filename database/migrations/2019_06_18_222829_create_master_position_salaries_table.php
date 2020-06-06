<?php

use App\Enum\Staff\StaffStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterPositionSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_position_salaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position_id')->unsigned()->index();
            $table->foreign('position_id')->references('id')->on('master_staff_positions');
            $table->integer('min_work_length')->default(0);
            $table->integer('max_work_length')->default(0);
            $table->enum('status', StaffStatus::keys())->default('Active');
            $table->decimal('basic_salary', 18, 2)->default(0);
            $table->decimal('daily', 18, 2)->default(0);
            $table->decimal('functional', 18, 2)->default(0);
            $table->decimal('health', 18, 2)->default(0);
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
        Schema::dropIfExists('master_position_salaries');
    }
}
