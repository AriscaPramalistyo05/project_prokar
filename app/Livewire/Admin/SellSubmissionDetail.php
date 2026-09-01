<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\SellSubmission;
use App\Models\SellSubmissionImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Layout('layouts.admin')]
class SellSubmissionDetail extends Component
{
    use WithFileUploads, Toast;

    public SellSubmission $submission;
    public $offered_price;
    public $agreed_price;
    public $new_media = [];

    public function mount(SellSubmission $sellSubmission)
    {
        $this->submission = $sellSubmission;
        $this->submission->load(['category', 'sellSubmissionImages']);
        $this->offered_price = $sellSubmission->offered_price;
        $this->agreed_price = $sellSubmission->agreed_price;
    }

    public function uploadMedia()
    {
        $this->validate([
            'new_media.*' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv|max:51200',
        ]);

        foreach ($this->new_media as $file) {
            $mime = $file->getMimeType();
            $ext = strtolower($file->getClientOriginalExtension());
            $isVideo = str_starts_with($mime, 'video/') || in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
            $type = $isVideo ? 'video' : 'photo';

            $path = $file->store('sell_submissions', 'public');

            SellSubmissionImage::create([
                'sell_submission_id' => $this->submission->id,
                'path' => $path,
                'type' => $type,
            ]);
        }

        $this->new_media = [];
        $this->mount($this->submission);
        $this->toast(type: 'success', title: 'Foto / Video berhasil ditambahkan ke galeri!');
    }

    public function deleteMedia(int $mediaId)
    {
        $media = SellSubmissionImage::where('sell_submission_id', $this->submission->id)
            ->where('id', $mediaId)
            ->first();

        if ($media) {
            if (Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }
            $media->delete();
            $this->mount($this->submission);
            $this->toast(type: 'success', title: 'Media berhasil dihapus.');
        }
    }

    public function updateStatus($status)
    {
        $this->submission->update(['status' => $status]);
        $this->mount($this->submission);
    }

    public function saveOfferedPrice()
    {
        $this->validate([
            'offered_price' => 'required|numeric|min:0'
        ]);

        $this->submission->update([
            'offered_price' => $this->offered_price,
            'status' => 'negotiating'
        ]);
        $this->mount($this->submission);
    }

    public function saveAgreedPrice()
    {
        $this->validate([
            'agreed_price' => 'required|numeric|min:0'
        ]);

        $this->submission->update([
            'agreed_price' => $this->agreed_price,
            'status' => 'accepted'
        ]);
        $this->mount($this->submission);
    }
    
    public function markPhysicalCheck()
    {
        $this->submission->update([
            'physical_check_at' => now(),
            'status' => 'accepted'
        ]);
        $this->mount($this->submission);
    }
    
    public function markPaid(string $method = 'cash')
    {
        $this->submission->update([
            'payment_at' => now(),
            'payment_method' => $method,
            'status' => 'paid'
        ]);
        $this->mount($this->submission);

        // Kirim email bukti transaksi jual barang selesai ke pelanggan
        if (!empty($this->submission->customer_email)) {
            try {
                \Illuminate\Support\Facades\Mail::to($this->submission->customer_email)
                    ->send(new \App\Mail\SellSubmissionCompletedMail($this->submission));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Failed sending sell submission completed email {$this->submission->submission_code}: " . $e->getMessage());
            }
        }

        $methodLabel = $method === 'transfer' ? 'Transfer Bank' : 'Tunai di Tempat (Cash)';
        $this->toast(type: 'success', title: "Pembayaran ke pelanggan selesai ({$methodLabel}).");
    }

    public function markNeedsRepair()
    {
        $this->submission->update(['status' => 'in_repair']);
        $this->mount($this->submission);
    }

    public function markRepairDone()
    {
        $this->submission->update(['status' => 'ready_for_sale']);
        $this->mount($this->submission);
    }

    public function convertToProduct()
    {
        return DB::transaction(function () {
            // Check if already converted
            if ($this->submission->converted_product_id) {
                return redirect()->route('admin.products.edit', $this->submission->converted_product_id);
            }

            $product = Product::create([
                'category_id' => $this->submission->category_id,
                'name' => $this->submission->device_brand . ' ' . ($this->submission->device_model ?: 'Elektronik'),
                'slug' => str()->slug($this->submission->device_brand . ' ' . $this->submission->device_model . '-' . uniqid()),
                'brand' => $this->submission->device_brand,
                'model' => $this->submission->device_model,
                'description' => "Barang masuk dari pelanggan " . $this->submission->customer_name . ".\n\n" . ($this->submission->description ?: ''),
                'condition_notes' => 'Kondisi: ' . ucfirst($this->submission->condition ?? 'Bekas Berkualitas'),
                'price' => $this->submission->agreed_price ?: $this->submission->offered_price ?: 0,
                'stock' => 1,
                'status' => 'available',
            ]);

            // Salin foto barang yang diupload customer ke galeri produk
            foreach ($this->submission->sellSubmissionImages as $index => $media) {
                if ($media->type === 'photo' || $media->type === 'image') {
                    \App\Models\ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $media->path,
                        'type' => 'image',
                        'is_primary' => $index === 0,
                        'order' => $index,
                    ]);
                }
            }

            $this->submission->update([
                'converted_product_id' => $product->id,
                'status' => 'ready_for_sale',
            ]);

            session()->flash('success', 'Barang berhasil dikonversi ke Kelola Produk. Silakan lengkapi harga jual dan detail lainnya.');
            return redirect()->route('admin.products.edit', $product->id);
        });
    }

    public function render()
    {
        return view('livewire.admin.sell-submission-detail');
    }
}
