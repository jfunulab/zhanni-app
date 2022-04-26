<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemittancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remittances', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('recipient_id')->nullable()->index();
            $table->unsignedBigInteger('funding_account_id')->nullable()->index();
            $table->unsignedBigInteger('exchange_rate_id')->nullable()->index();
            $table->unsignedBigInteger('pickup_bank_id')->nullable()->index();
            $table->string('reason')->nullable();
            $table->float('base_amount');
            $table->float('fee');
            $table->string('base_currency', 10);
            $table->float('amount_to_remit');
            $table->string('currency_to_remit');
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->foreign('recipient_id')->on('transfer_recipients')->references('id')->onDelete('set null');
            $table->foreign('pickup_bank_id')->on('banks')->references('id')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remittances');
    }
}
