<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductForm extends Component
{
    use WithFileUploads;

    public $product = null; // product model instance if editing
    public $isEdit = false;

    // Form attributes
    public $category_id = '';
    public $name = '';
    public $brand = '';
    public $model = '';
    public $description = '';
    
    // Condition handling
    public $condition_type = 'Seperti Baru'; // Predefined templates or 'custom'
    public $custom_condition = '';
    public $condition_color = 'green';

    public $condition_notes = '';
    public $price = '';
    public $promo_price = '';
    public $stock = 1;
    public $weight = 1000;
    public $length = '';
    public $width = '';
    public $height = '';
    public $status = 'available';
    public $is_promo = false;

    // SEO Metadata
    public $meta_title = '';
    public $meta_description = '';

    // File Uploads - single media array for both images and videos
    public $media = [];
    public $existingPhotos = [];
    public $replacingPhoto = [];

    // Predefined templates helper
    public $conditionTemplates = [
        'Seperti Baru' => ['label' => 'Seperti Baru', 'color' => 'green'],
        'Kondisi Prima' => ['label' => 'Kondisi Prima', 'color' => 'emerald'],
        'Kondisi Baik' => ['label' => 'Kondisi Baik', 'color' => 'blue'],
        'Lecet Pemakaian' => ['label' => 'Lecet Pemakaian', 'color' => 'yellow'],
        'Kondisi Minus Body' => ['label' => 'Kondisi Minus Body', 'color' => 'red'],
        'custom' => ['label' => 'Custom...', 'color' => 'blue']
    ];

    public function mount(Product $product = null)
    {
        if ($product && $product->exists) {
            $this->product = $product;
            $this->isEdit = true;
            $this->category_id = $product->category_id;
            $this->name = $product->name;
            $this->brand = $product->brand;
            $this->model = $product->model;
            $this->description = $product->description;
            $this->condition_notes = $product->condition_notes;
            $this->price = (float)$product->price;
            $this->promo_price = $product->promo_price ? (float)$product->promo_price : '';
            $this->stock = $product->stock;
            $this->weight = $product->weight ?: 1000;
            $this->length = $product->length ?: '';
            $this->width = $product->width ?: '';
            $this->height = $product->height ?: '';
            $this->status = $product->status;
            $this->is_promo = (bool)$product->is_promo;
            $this->meta_title = $product->meta_title;
            $this->meta_description = $product->meta_description;
            
            // Check if condition matches template
            $presetKeys = ['Seperti Baru', 'Kondisi Prima', 'Kondisi Baik', 'Lecet Pemakaian', 'Kondisi Minus Body'];
            if (in_array($product->condition, $presetKeys)) {
                $this->condition_type = $product->condition;
                $this->condition_color = $product->condition_color;
            } else {
                $this->condition_type = 'custom';
                $this->custom_condition = $product->condition;
                $this->condition_color = $product->condition_color;
            }

            $this->existingPhotos = $product->productImages()->orderBy('order')->get()->toArray();
        } else {
            $this->isEdit = false;
        }
    }

    public function updatedConditionType($value)
    {
        if ($value !== 'custom') {
            $this->condition_color = $this->conditionTemplates[$value]['color'];
            $this->custom_condition = '';
        }
    }

    public function updatedMedia()
    {
        if (!is_array($this->media)) {
            $this->media = $this->media ? [$this->media] : [];
        }
    }

    public function updatedReplacingPhoto($value, $photoId)
    {
        if ($value && method_exists($value, 'store')) {
            $photo = ProductImage::findOrFail($photoId);
            $cleanPath = ltrim($photo->path, '/');
            if (str_starts_with($cleanPath, 'storage/')) {
                $cleanPath = substr($cleanPath, 8);
            }
            if (!str_starts_with($cleanPath, 'http')) {
                Storage::disk('public')->delete($cleanPath);
            }

            $extension = strtolower($value->getClientOriginalExtension());
            $videoExts = ['mp4', 'mov', 'avi', 'webm'];
            $mediaType = in_array($extension, $videoExts) ? 'video' : 'image';
            $path = \App\Services\ImageOptimizer::optimizeAndStore($value, 'products', 1200, 80);

            $photo->update([
                'path' => $path,
                'type' => $mediaType,
            ]);

            if ($this->product) {
                $this->existingPhotos = $this->product->productImages()->orderBy('order')->get()->toArray();
            }
            $this->dispatch('mary-toast', type: 'success', title: 'Foto berhasil diganti!');
        }
    }

    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:200',
            'brand' => 'required|string|max:100',
            'model' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'condition_notes' => 'nullable|string',
            'condition_type' => 'required|string',
            'custom_condition' => 'required_if:condition_type,custom|nullable|string|max:20',
            'condition_color' => 'required|string|in:green,emerald,blue,yellow,red',
            'price' => 'required|numeric|min:0',
            'promo_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0|max:99',
            'weight' => 'required|integer|min:1',
            'length' => 'nullable|integer|min:1',
            'width' => 'nullable|integer|min:1',
            'height' => 'nullable|integer|min:1',
            'status' => 'required|string|in:available,reserved,sold,unavailable',
            'is_promo' => 'required|boolean',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'media' => 'nullable',
            'media.*' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm',
                'max:51200',
            ],
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Silakan pilih kategori produk.',
            'name.required' => 'Nama produk wajib diisi.',
            'brand.required' => 'Brand / Merk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'stock.required' => 'Jumlah stok wajib diisi.',
            'weight.required' => 'Berat timbangan wajib diisi.',
            'custom_condition.required_if' => 'Teks badge keadaan kustom wajib diisi.',
            'custom_condition.max' => 'Teks badge kustom maksimal :max karakter.',
            'promo_price.lt' => 'Harga promo harus lebih kecil dari harga normal.',
            'stock.max' => 'Stok maksimal 2 digit (maks 99).',
            'media.*.max' => 'Ukuran file mentah maksimal 50MB.',
        ];
    }

    public function removeMedia($index)
    {
        if (isset($this->media[$index])) {
            unset($this->media[$index]);
            $this->media = array_values($this->media);
        }
    }

    public function deleteExistingPhoto($photoId)
    {
        $photo = ProductImage::findOrFail($photoId);
        $cleanPath = ltrim($photo->path, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }
        if (!str_starts_with($cleanPath, 'http')) {
            Storage::disk('public')->delete($cleanPath);
        }
        $wasPrimary = $photo->is_primary;
        $productId = $photo->product_id;
        $photo->delete();

        if ($wasPrimary) {
            $firstRemaining = ProductImage::where('product_id', $productId)->first();
            if ($firstRemaining) {
                $firstRemaining->update(['is_primary' => true]);
            }
        }

        if ($this->product) {
            $this->existingPhotos = $this->product->productImages()->orderBy('order')->get()->toArray();
        } else {
            $this->existingPhotos = array_filter($this->existingPhotos, fn($p) => $p['id'] !== $photoId);
        }
        
        $this->dispatch('mary-toast', type: 'success', title: 'Foto berhasil dihapus');
    }

    public function setPrimaryPhoto($photoId)
    {
        ProductImage::where('product_id', $this->product->id)->update(['is_primary' => false]);
        ProductImage::where('id', $photoId)->update(['is_primary' => true]);

        // reload photos
        $this->existingPhotos = $this->product->productImages()->orderBy('order')->get()->toArray();
        $this->dispatch('mary-toast', type: 'success', title: 'Foto utama berhasil diubah');
    }

    public function save()
    {
        if (!is_array($this->media)) {
            $this->media = $this->media ? [$this->media] : [];
        }

        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstError = reset($errors);
            $msg = is_array($firstError) ? $firstError[0] : (string)$firstError;
            $this->dispatch('mary-toast', type: 'error', title: 'Validasi Gagal', text: $msg);
            return;
        }

        // Determine condition text
        $finalCondition = $this->condition_type === 'custom' 
            ? ($this->custom_condition ?: 'Kondisi Baik') 
            : ($this->conditionTemplates[$this->condition_type]['label'] ?? 'Seperti Baru');

        $productData = [
            'category_id' => (int)$this->category_id,
            'name' => $this->name,
            'brand' => $this->brand,
            'model' => $this->model ? $this->model : null,
            'description' => $this->description ? $this->description : null,
            'condition' => $finalCondition,
            'condition_color' => $this->condition_color ?: 'green',
            'condition_notes' => $this->condition_notes ? $this->condition_notes : null,
            'price' => (float)$this->price,
            'promo_price' => ($this->promo_price !== '' && $this->promo_price !== null) ? (float)$this->promo_price : null,
            'stock' => (int)$this->stock,
            'weight' => (int)($this->weight ?: 1000),
            'length' => $this->length ? (int)$this->length : null,
            'width' => $this->width ? (int)$this->width : null,
            'height' => $this->height ? (int)$this->height : null,
            'status' => $this->status ?: 'available',
            'is_promo' => (bool)$this->is_promo,
            'meta_title' => $this->meta_title ? $this->meta_title : null,
            'meta_description' => $this->meta_description ? $this->meta_description : null,
        ];

        if ($this->isEdit) {
            $this->product->update($productData);
            $product = $this->product;
        } else {
            $baseSlug = Str::slug($this->name) ?: 'produk-' . rand(100, 999);
            $slug = $baseSlug . '-' . rand(100, 999);
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . rand(100, 9999);
            }
            $productData['slug'] = $slug;
            $product = Product::create($productData);
        }

        // Handle uploaded media (images & videos)
        if (is_array($this->media) && count($this->media) > 0) {
            $existingCount = $this->isEdit ? count($this->existingPhotos) : 0;
            $hasPrimary = $this->isEdit && ProductImage::where('product_id', $product->id)->where('is_primary', true)->exists();

            foreach ($this->media as $index => $file) {
                if (!$file || !method_exists($file, 'store')) continue;

                $extension = strtolower($file->getClientOriginalExtension());
                $videoExts = ['mp4', 'mov', 'avi', 'webm'];
                $mediaType = in_array($extension, $videoExts) ? 'video' : 'image';

                $path = \App\Services\ImageOptimizer::optimizeAndStore($file, 'products', 1200, 80);
                $isPrimary = false;

                if ($mediaType === 'video' && $extension === 'mp4') {
                    \App\Services\Mp4FastStart::process(storage_path('app/public/' . $path));
                }

                if (!$hasPrimary && $index === 0) {
                    $isPrimary = true;
                    $hasPrimary = true;
                }

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'type' => $mediaType,
                    'is_primary' => $isPrimary,
                    'order' => $existingCount + $index,
                ]);
            }
        }

        if ($this->isEdit) {
            session()->flash('message', 'Data produk "' . $product->name . '" telah berhasil diperbarui.');
            session()->flash('success_title', 'Produk Berhasil Diperbarui!');
        } else {
            session()->flash('message', 'Produk baru "' . $product->name . '" telah berhasil ditambahkan ke katalog.');
            session()->flash('success_title', 'Produk Baru Berhasil Dibuat!');
        }

        return redirect()->route('admin.products.index');
    }

    private function compressImageFile(string $fullPath): void
    {
        if (!file_exists($fullPath) || filesize($fullPath) < 250 * 1024) {
            return;
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $img = null;

        if ($ext === 'jpg' || $ext === 'jpeg') {
            $img = @imagecreatefromjpeg($fullPath);
        } elseif ($ext === 'png') {
            $img = @imagecreatefrompng($fullPath);
        }

        if (!$img) return;

        $width = imagesx($img);
        $height = imagesy($img);
        $maxDim = 1200;

        if ($width > $maxDim || $height > $maxDim) {
            $ratio = min($maxDim / $width, $maxDim / $height);
            $newWidth = (int) round($width * $ratio);
            $newHeight = (int) round($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            if ($ext === 'png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }
            imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($img);
            $img = $resized;
        }

        if ($ext === 'png') {
            imagepng($img, $fullPath, 8);
        } else {
            imagejpeg($img, $fullPath, 82);
        }

        imagedestroy($img);
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.product-form', [
            'categories' => $categories,
        ])->layout('layouts.admin');
    }
}
