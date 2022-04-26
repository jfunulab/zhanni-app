<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remittance_id');
            $table->string('reference_id')->nullable();
            $table->morphs('sourceable');
            $table->float('amount');
            $table->float('amount_in_cents');
            $table->integer('base_amount_transferred_to_zhanni')->nullable();
            $table->integer('fee_amount_transferred_to_zhanni')->nullable();
            $table->string('currency');
            $table->string('status');
            $table->string('processing_type')->nullable();
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
        Schema::dropIfExists('credit_payments');
    }
}
