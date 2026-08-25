<?php

namespace Tests\Feature;

use App\Services\ImageOptimizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageOptimizerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_image_optimizer_converts_jpg_to_webp_format(): void
    {
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600);

        $storedPath = ImageOptimizer::optimizeAndStore($file, 'products', 1200, 80);

        $this->assertStringEndsWith('.webp', $storedPath);
        $this->assertTrue(Storage::disk('public')->exists($storedPath));
    }

    public function test_image_optimizer_resizes_image_larger_than_max_width(): void
    {
        $largeFile = UploadedFile::fake()->image('large_photo.png', 2000, 1500);

        $storedPath = ImageOptimizer::optimizeAndStore($largeFile, 'settings', 800, 80);

        $this->assertStringEndsWith('.webp', $storedPath);
        $this->assertTrue(Storage::disk('public')->exists($storedPath));
    }

    public function test_image_optimizer_preserves_videos_without_webp_conversion(): void
    {
        $videoFile = UploadedFile::fake()->create('sample.mp4', 500, 'video/mp4');

        $storedPath = ImageOptimizer::optimizeAndStore($videoFile, 'products');

        $this->assertStringEndsWith('.mp4', $storedPath);
        $this->assertTrue(Storage::disk('public')->exists($storedPath));
    }
}
