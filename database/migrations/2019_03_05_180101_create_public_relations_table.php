<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->index();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->string('nih')->unique();
            $table->string('name');
            $table->integer('status_id')->unsigned()->index();
            $table->foreign('status_id')->references('id')->on('master_public_relation_statuses');

            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('job')->nullable();
            $table->text('address')->nullable();

            $table->date('registered_date');
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
        Schema::dropIfExists('public_relations');
    }
}
