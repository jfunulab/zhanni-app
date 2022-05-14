<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationCategoryDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_category_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('verification_category_id');
            $table->unsignedBigInteger('verification_category_document_id');
            $table->timestamps();
            $table->foreign('verification_category_id')
                ->references('id')
                ->on('verification_categories')
                ->onDelete('cascade');

            $table->foreign('verification_category_document_id', 'verification_category_document_id_foreign')
                ->references('id')->on('verification_documents')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_category_documents');
    }
}
