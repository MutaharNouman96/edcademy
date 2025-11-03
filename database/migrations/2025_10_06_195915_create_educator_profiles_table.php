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
        Schema::create('educator_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Step 2 Fields
            $table->string('primary_subject');
            $table->json('teaching_levels')->nullable();  // Multiple select
            $table->decimal('hourly_rate', 8, 2);
            $table->text('certifications')->nullable();
            $table->string('preferred_teaching_style')->nullable();

            // Step 3 Fields
            $table->string('govt_id_path')->nullable();
            $table->string('degree_proof_path')->nullable();
            $table->string('intro_video_path')->nullable();

            $table->boolean('consent_verified')->default(false);

            // Status & verification
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');

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
        Schema::dropIfExists('educator_profiles');
    }
};
