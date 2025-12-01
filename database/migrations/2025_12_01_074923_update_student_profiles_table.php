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
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['pronouns', 'location', 'website']);
            $table->string('phone')->nullable()->after('user_id');
            $table->string('language')->nullable()->after('phone');
            $table->string('timezone')->nullable()->after('language');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('pronouns')->nullable()->after('gender');
            $table->string('location')->nullable()->after('pronouns');
            $table->string('website')->nullable()->after('location');
            $table->dropColumn(['phone', 'language', 'timezone']);
        });
    }
};
