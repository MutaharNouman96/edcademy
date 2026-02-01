<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('title');
            }
        });

        // Backfill slugs for existing rows (if any).
        $rows = DB::table('blogs')->select('id', 'title', 'slug')->get();

        foreach ($rows as $row) {
            if (!empty($row->slug)) {
                continue;
            }

            $base = Str::slug((string) $row->title);
            $slug = $base ?: Str::random(8);

            $i = 1;
            while (DB::table('blogs')->where('slug', $slug)->exists()) {
                $i++;
                $slug = ($base ?: 'blog') . '-' . $i;
            }

            DB::table('blogs')->where('id', $row->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (Schema::hasColumn('blogs', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
};

