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
        Schema::table('video_stats', function (Blueprint $table) {
            //
            $table->string("average_watch_time")->default('00:00:00');
            $table->float("completion_rate")->default('0');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_stats', function (Blueprint $table) {
            //
            $table->dropColumn('average_watch_time');
            $table->dropColumn('completion_rate');
        });
    }
};
