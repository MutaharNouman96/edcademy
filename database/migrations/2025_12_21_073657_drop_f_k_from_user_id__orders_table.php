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
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $column) {
            // 1. Drop the foreign key first
            $column->dropForeign(['user_id']);
        });

        Schema::table('orders', function (Blueprint $column) {
            // 2. Change the type and make it nullable
            $column->string('user_id', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $column) {
            // To reverse: convert back to unsigned big integer (common for FKs)
            $column->unsignedBigInteger('user_id')->nullable(false)->change();

            // Re-add the foreign key
            $column->foreign('user_id')->references('id')->on('users');
        });
    }
};
