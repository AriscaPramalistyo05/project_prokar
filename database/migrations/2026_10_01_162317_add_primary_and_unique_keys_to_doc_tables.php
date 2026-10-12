<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $pdo = DB::connection()->getPdo();

        // Check if doc_categories has primary key
        $catPk = $pdo->query("SHOW KEYS FROM `doc_categories` WHERE Key_name = 'PRIMARY'")->fetchAll();
        if (empty($catPk)) {
            $pdo->exec("SET FOREIGN_KEY_CHECKS=0;");
            $pdo->exec("CREATE TEMPORARY TABLE temp_cat AS SELECT DISTINCT * FROM doc_categories;");
            $pdo->exec("TRUNCATE TABLE doc_categories;");
            $pdo->exec("INSERT INTO doc_categories SELECT * FROM temp_cat;");
            $pdo->exec("DROP TEMPORARY TABLE temp_cat;");
            $pdo->exec("ALTER TABLE doc_categories MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY;");
            $pdo->exec("SET FOREIGN_KEY_CHECKS=1;");
        }

        // Check unique slug on doc_categories
        $catSlug = $pdo->query("SHOW KEYS FROM `doc_categories` WHERE Key_name = 'doc_categories_slug_unique'")->fetchAll();
        if (empty($catSlug)) {
            $pdo->exec("ALTER TABLE doc_categories ADD UNIQUE KEY doc_categories_slug_unique (slug);");
        }

        // Check if doc_articles has primary key
        $artPk = $pdo->query("SHOW KEYS FROM `doc_articles` WHERE Key_name = 'PRIMARY'")->fetchAll();
        if (empty($artPk)) {
            $pdo->exec("SET FOREIGN_KEY_CHECKS=0;");
            $pdo->exec("CREATE TEMPORARY TABLE temp_art AS SELECT DISTINCT * FROM doc_articles;");
            $pdo->exec("TRUNCATE TABLE doc_articles;");
            $pdo->exec("INSERT INTO doc_articles SELECT * FROM temp_art;");
            $pdo->exec("DROP TEMPORARY TABLE temp_art;");
            $pdo->exec("ALTER TABLE doc_articles MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY;");
            $pdo->exec("SET FOREIGN_KEY_CHECKS=1;");
        }

        // Check unique slug on doc_articles
        $artSlug = $pdo->query("SHOW KEYS FROM `doc_articles` WHERE Key_name = 'doc_articles_slug_unique'")->fetchAll();
        if (empty($artSlug)) {
            $pdo->exec("ALTER TABLE doc_articles ADD UNIQUE KEY doc_articles_slug_unique (slug);");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep primary and unique keys intact to prevent data corruption
    }
};
