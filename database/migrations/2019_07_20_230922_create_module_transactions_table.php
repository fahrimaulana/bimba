<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->index();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->string('receipt')->nullable();
            $table->dateTime('date');
            $table->integer('week')->nullable();
            $table->integer('module_id')->nullable()->unsigned()->index();
            $table->foreign('module_id')->references('id')->on('modules');
            $table->decimal('module_price', 18, 2);
            $table->integer('qty');
            $table->enum('type', ['in', 'out', 'opname']);
            $table->integer('staff_id')->nullable()->unsigned()->index();
            $table->foreign('staff_id')->references('id')->on('staff');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('module_transactions');
    }
}
