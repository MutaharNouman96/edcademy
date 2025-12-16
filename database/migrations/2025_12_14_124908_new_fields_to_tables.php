<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. educator_profiles
        try {
            Schema::table('educator_profiles', function (Blueprint $table) {
                // NOTE: The 'after' constraint is handled differently/may not be available
                // or necessary in some ORMs, but we can ensure the columns are added.
                $table->text('bio')->nullable()->after('featured');
                $table->text('location')->nullable()->after('featured');
            });

            // 2. users
            Schema::table('users', function (Blueprint $table) {
                $table->text('profile_picture')->nullable();
            });

            // 3. lessons
            Schema::table('lessons', function (Blueprint $table) {
                $table->text('popular')->nullable();
            });
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. educator_profiles
        Schema::table('educator_profiles', function (Blueprint $table) {
            $table->dropColumn('location');
            $table->dropColumn('bio');
        });

        // 2. users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_picture');
        });

        // 3. lessons
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('popular');
        });
    }
};
