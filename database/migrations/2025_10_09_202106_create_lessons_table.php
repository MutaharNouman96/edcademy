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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('type')->nullable();

            $table->string('title');
            $table->integer('duration')->nullable(); // minutes
            $table->decimal('price', 8, 2)->default(0);
            $table->boolean('free')->default(false);
            $table->enum('status', ['Draft', 'Published'])->default('Draft');
            $table->boolean('preview')->default(false);

            // media
            $table->string('video_path')->nullable();        // uploaded video
            $table->string('video_link')->nullable();        // external link (YouTube, Vimeo)
            $table->json('materials')->nullable();           // PDF, PPT
            $table->json('worksheets')->nullable();          // PDF, DOC
            $table->json('resources')->nullable();           // extra links
            $table->json('assignments')->nullable();         // assignment details

            $table->text('notes')->nullable();
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
        Schema::dropIfExists('lessons');
    }
};
