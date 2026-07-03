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
    public $status = 'available';
    public $is_promo = false;

    // SEO Metadata
    public $meta_title = '';
    public $meta_description = '';

    // File Uploads
    public $newPhotos = [];
    public $existingPhotos = [];

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
            $this->status = $product->status;
            $this->is_promo = $product->is_promo;
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
            'custom_condition' => 'required_if:condition_type,custom|nullable|string|max:20', // Validation: max 20 characters to prevent breaking UI
            'condition_color' => 'required|string|in:green,emerald,blue,yellow,red',
            'price' => 'required|numeric|min:0',
            'promo_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0|max:99', // max 2 digits
            'status' => 'required|string|in:available,reserved,sold,unavailable',
            'is_promo' => 'required|boolean',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:300',
            'newPhotos.*' => 'nullable|image|max:2048', // 2MB max
        ];
    }

    public function messages()
    {
        return [
            'custom_condition.required_if' => 'Teks badge keadaan kustom wajib diisi.',
            'custom_condition.max' => 'Teks badge kustom maksimal :max karakter untuk menjaga agar tampilan UI tidak pecah.',
            'promo_price.lt' => 'Harga promo harus lebih kecil dari harga normal.',
            'stock.max' => 'Stok maksimal 2 digit (maks 99).',
        ];
    }

    public function deleteExistingPhoto($photoId)
    {
        $photo = ProductImage::findOrFail($photoId);
        if (Str::startsWith($photo->path, 'storage/')) {
            Storage::disk('public')->delete(Str::after($photo->path, 'storage/'));
        }
        $photo->delete();
        $this->existingPhotos = array_filter($this->existingPhotos, fn($p) => $p['id'] !== $photoId);
        
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
        $this->validate();

        // Determine condition text
        $finalCondition = $this->condition_type === 'custom' ? $this->custom_condition : $this->conditionTemplates[$this->condition_type]['label'];

        $productData = [
            'category_id' => $this->category_id,
            'name' => $this->name,
            'slug' => $this->isEdit ? $this->product->slug : Str::slug($this->name) . '-' . rand(100, 999),
            'brand' => $this->brand,
            'model' => $this->model ? $this->model : null,
            'description' => $this->description ? $this->description : null,
            'condition' => $finalCondition,
            'condition_color' => $this->condition_color,
            'condition_notes' => $this->condition_notes ? $this->condition_notes : null,
            'price' => $this->price,
            'promo_price' => ($this->promo_price !== '' && $this->promo_price !== null) ? $this->promo_price : null,
            'stock' => $this->stock,
            'status' => $this->status,
            'is_promo' => $this->is_promo,
            'meta_title' => $this->meta_title ? $this->meta_title : null,
            'meta_description' => $this->meta_description ? $this->meta_description : null,
        ];

        if ($this->isEdit) {
            $this->product->update($productData);
            $product = $this->product;
            session()->flash('message', 'Produk berhasil diperbarui.');
        } else {
            $product = Product::create($productData);
            session()->flash('message', 'Produk berhasil dibuat.');
        }

        // Handle uploaded images
        if (count($this->newPhotos) > 0) {
            foreach ($this->newPhotos as $index => $photo) {
                $path = $photo->store('products', 'public');
                $isPrimary = false;

                if (!$this->isEdit && $index === 0) {
                    $isPrimary = true;
                } elseif ($this->isEdit && !ProductImage::where('product_id', $product->id)->where('is_primary', true)->exists() && $index === 0) {
                    $isPrimary = true;
                }

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => 'storage/' . $path,
                    'is_primary' => $isPrimary,
                    'order' => $index + ($this->isEdit ? count($this->existingPhotos) : 0),
                ]);
            }
        }

        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.product-form', [
            'categories' => $categories,
        ])->layout('layouts.admin');
    }
}
