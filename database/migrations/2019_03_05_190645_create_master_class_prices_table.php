<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterClassPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_class_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('class_id')->unsigned()->index();
            $table->foreign('class_id')->references('id')->on('master_classes');
            $table->integer('grade_id')->unsigned()->index();
            $table->foreign('grade_id')->references('id')->on('master_grades');
            $table->decimal('price', 18, 2);
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
        Schema::dropIfExists('master_class_prices');
    }
}
