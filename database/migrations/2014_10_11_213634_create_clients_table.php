<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->string('code');
            $table->string('name');
            $table->enum('type', ['Default'])->default('Default');

            $table->string('staff_name');
            $table->string('phone');
            $table->string('email');
            $table->text('address');

            $table->string('account_bank');
            $table->string('account_number');
            $table->string('account_name');

            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
