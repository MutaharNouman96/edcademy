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
        Schema::create('escrows', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('educator_id');
            $table->float('amount')->unsigned();
            $table->string('status')->default("pending");
            $table->datetime('processed_at')->nullable();
            $table->string("processed_by")->nullable();
            $table->longText("description")->nullable();
            $table->unsignedBigInteger("payment_id")->nullable();

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
        Schema::dropIfExists('escrows');
    }
};
