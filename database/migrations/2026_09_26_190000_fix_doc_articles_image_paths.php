<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations to fix relative image paths in doc_articles content.
     */
    public function up(): void
    {
        // 1. Replace ../../../storage/ with /storage/
        DB::table('doc_articles')
            ->where('content', 'like', '%../../../storage/%')
            ->update([
                'content' => DB::raw("REPLACE(content, '../../../storage/', '/storage/')")
            ]);

        // 2. Replace ../../storage/ with /storage/ (if any exists)
        DB::table('doc_articles')
            ->where('content', 'like', '%../../storage/%')
            ->update([
                'content' => DB::raw("REPLACE(content, '../../storage/', '/storage/')")
            ]);

        // 3. Replace ../storage/ with /storage/ (if any exists)
        DB::table('doc_articles')
            ->where('content', 'like', '%../storage/%')
            ->update([
                'content' => DB::raw("REPLACE(content, '../storage/', '/storage/')")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible as root-relative path is the intended canonical format
    }
};
