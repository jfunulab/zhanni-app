<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('bid_id');
            $table->unsignedBigInteger('seller_id');
            $table->integer('minimum_amount');
            $table->integer('maximum_amount');
            $table->integer('rate');
            $table->string('origin_currency');
            $table->string('destination_currency');
            $table->bigInteger('buyer_funding_account_id')->nullable();
            $table->bigInteger('buyer_receiving_account_id')->nullable();
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
        Schema::dropIfExists('bid_orders');
    }
}
