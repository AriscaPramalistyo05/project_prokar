<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\SellSubmission;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class SellSubmissionDetail extends Component
{
    #[Layout('layouts.admin')]
    public SellSubmission $submission;
    public $offered_price;
    public $agreed_price;

    public function mount(SellSubmission $sellSubmission)
    {
        $this->submission = $sellSubmission;
        $this->submission->load(['category', 'sellSubmissionImages']);
        $this->offered_price = $sellSubmission->offered_price;
        $this->agreed_price = $sellSubmission->agreed_price;
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
    
    public function markPaid()
    {
        $this->submission->update([
            'payment_at' => now(),
            'status' => 'paid'
        ]);
        $this->mount($this->submission);
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
        DB::transaction(function () {
            // Check if already converted
            if ($this->submission->converted_product_id) {
                return redirect()->route('admin.products.edit', $this->submission->converted_product_id);
            }

            $product = Product::create([
                'category_id' => $this->submission->category_id,
                'name' => $this->submission->device_brand . ' ' . $this->submission->device_model,
                'slug' => str()->slug($this->submission->device_brand . ' ' . $this->submission->device_model . '-' . uniqid()),
                'brand' => $this->submission->device_brand,
                'model' => $this->submission->device_model,
                'description' => "Barang masuk dari " . $this->submission->customer_name . ".\n\n" . $this->submission->description . "\n" . $this->submission->admin_notes,
                'condition_notes' => $this->submission->condition,
                'price' => 0,
                'stock' => 1,
                'status' => 'available',
            ]);

            $this->submission->update([
                'converted_product_id' => $product->id,
            ]);

            return redirect()->route('admin.products.edit', $product->id);
        });
    }

    public function render()
    {
        return view('livewire.admin.sell-submission-detail');
    }
}
