<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('username')->nullable()->unique();
            $table->string('sila_username')->nullable();
            $table->integer('email_verification_code')->nullable();
            $table->timestamp('verification_code_expires_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('sila_key')->nullable();
            $table->string('sila_address')->nullable();
            $table->string('kyc_status')->nullable();
            $table->json('kyc_issues')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
