<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doc_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc_category_id')->constrained('doc_categories')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('doc_articles')->nullOnDelete();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image', 255)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('version', 20)->default('1.0');
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['doc_category_id', 'order']);
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doc_articles');
    }
};
