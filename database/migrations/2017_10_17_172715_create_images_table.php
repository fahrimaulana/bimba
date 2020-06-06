<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('entity');
            $table->string('url');
            $table->string('title')->nullable();
            $table->string('caption')->nullable();
            $table->text('content')->nullable();
            $table->string('link')->nullable();
            $table->string('type')->nullable();
            $table->enum('media_type', ['Image', 'Video', 'Youtube'])->default('image');
            $table->boolean('default')->default(false);
            $table->boolean('order')->default(1);
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
        Schema::dropIfExists('images');
    }
}
