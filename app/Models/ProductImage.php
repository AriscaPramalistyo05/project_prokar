<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'path', 'type', 'is_primary', 'order'];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function getUrlAttribute(): string
    {
        if (empty($this->path)) {
            return 'https://placehold.co/800x800/f3f4f6/9ca3af?text=No+Image';
        }

        if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
            return $this->path;
        }

        $cleanPath = ltrim($this->path, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        if ($this->type === 'video') {
            return route('video.stream', ['filename' => basename($cleanPath)]);
        }

        return asset('storage/' . $cleanPath);
    }
}
