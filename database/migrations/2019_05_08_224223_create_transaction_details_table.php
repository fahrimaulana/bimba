<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id')->unsigned()->index();
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->integer('qty');
            $table->decimal('price', 18, 2);
            $table->integer('voucher_id')->nullable()->unsigned()->index();
            $table->foreign('voucher_id')->references('id')->on('vouchers');
            $table->decimal('discount', 18, 2)->nullable();
            $table->decimal('total', 18, 2);
            $table->enum('category', ['Registration', 'SPP', 'Product', 'Event', 'Others']);
            $table->integer('product_id');
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
        Schema::dropIfExists('transaction_details');
    }
}
