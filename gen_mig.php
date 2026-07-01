<?php

$migrations = [
    'create_categories' => <<<SCHEMA
            \$table->id();
            \$table->string('name', 100);
            \$table->string('slug', 120)->unique();
            \$table->string('icon', 255)->nullable();
            \$table->timestamps();
SCHEMA,
    'create_products' => <<<SCHEMA
            \$table->id();
            \$table->foreignId('category_id')->constrained();
            \$table->string('name', 200);
            \$table->string('slug', 220)->unique();
            \$table->string('brand', 100);
            \$table->string('model', 100)->nullable();
            \$table->text('description')->nullable();
            \$table->text('condition_notes')->nullable();
            \$table->decimal('price', 12, 2);
            \$table->decimal('promo_price', 12, 2)->nullable();
            \$table->tinyInteger('stock')->unsigned()->default(1);
            \$table->enum('status', ['available','reserved','sold','unavailable'])->default('available');
            \$table->boolean('is_promo')->default(false);
            \$table->string('meta_title', 200)->nullable();
            \$table->string('meta_description', 300)->nullable();
            \$table->timestamps();
            \$table->softDeletes();
SCHEMA,
    'create_product_images' => <<<SCHEMA
            \$table->id();
            \$table->foreignId('product_id')->constrained();
            \$table->string('path', 255);
            \$table->boolean('is_primary')->default(false);
            \$table->tinyInteger('order')->unsigned()->default(0);
            \$table->timestamps();
SCHEMA,
    'create_orders' => <<<SCHEMA
            \$table->id();
            \$table->string('order_code', 50)->unique();
            \$table->foreignId('user_id')->nullable()->constrained();
            \$table->string('customer_name', crushingly long line, will continue in next thinking...
];

$directory = 'database/migrations/';

// ... rest of script ...
// Find existing migration files and replace their schema
