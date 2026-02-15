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
        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->unsignedSmallInteger('max_sessions_per_day')->default(6)->after('hourly_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->dropColumn('max_sessions_per_day');
        });
    }
};
