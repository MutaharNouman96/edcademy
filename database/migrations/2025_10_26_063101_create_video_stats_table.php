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
        Schema::create('video_stats', function (Blueprint $table) {
            $table->id();
            $table->string('video_id');
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);

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
        Schema::dropIfExists('video_stats');
    }
};
