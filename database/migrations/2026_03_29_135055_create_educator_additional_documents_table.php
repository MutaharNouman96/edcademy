<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educator_additional_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('educator_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('document_path')->nullable();
            $table->string('document_type')->nullable();
            $table->string('document_name')->nullable();
            $table->string('document_size')->nullable();
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
        Schema::dropIfExists('educator_additional_documents');
    }
};
