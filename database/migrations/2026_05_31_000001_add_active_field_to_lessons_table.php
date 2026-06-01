<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds the per-lesson "active" flag. A lesson is only surfaced to students /
     * the public site once an admin has verified it (manually toggled it on, or
     * implicitly when the parent course is approved). New lessons are created
     * inactive by default and must be approved.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Default false: every freshly created lesson awaits admin verification.
            $table->boolean('active')->default(false)->after('status');
        });

        // Backfill: keep all pre-existing lessons visible so this change does not
        // silently hide content that is already live on the platform.
        DB::table('lessons')->update(['active' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
};
