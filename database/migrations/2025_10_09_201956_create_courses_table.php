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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // educator

            // Basic info
            $table->string('title');
            $table->text('description');
            $table->string('subject');
            $table->string('level')->nullable();
            $table->string('language')->nullable();

            // Pricing
            $table->decimal('price', 8, 2)->default(0);
            $table->boolean('is_free')->default(false);

            // Course type and details
            $table->string('duration')->nullable();
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->nullable();
            $table->enum('type', ['module', 'video', 'live'])->default('module');

            $table->timestamp('schedule_date')->nullable();

            // Media and tags
            $table->string('thumbnail')->nullable();
            

            // Publishing options
            $table->enum('publish_option', ['now', 'schedule', 'draft'])->default('draft');
            $table->timestamp('publish_date')->nullable();
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            //drip
            $table->boolean('drip')->default(false);
            $table->string('drip_duration')->nullable();

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
        Schema::dropIfExists('courses');
    }
};
