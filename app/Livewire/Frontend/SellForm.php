<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\SellSubmission;
use App\Models\SellSubmissionImage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SellForm extends Component
{
    use WithFileUploads;

    public $nama = '';
    public $email = '';
    public $whatsapp = '';
    
    // Address Fields
    public $province_id = '';
    public $regency_id = '';
    public $district_id = '';
    public $village_id = '';
    public $address_detail = '';

    public $kategori = '';
    public $merek = '';
    public $kondisi = '';
    public $deskripsi = '';
    public $media = [];
    public $submitted = false;
    public $newServiceCode = '';
    public $submittedWhatsapp = '';

    protected function rules()
    {
        return [
            'nama' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:150',
            'whatsapp' => ['required', 'string', new \App\Rules\IndonesianPhone()],
            'province_id' => 'required',
            'regency_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
            'address_detail' => 'required|string|min:10',
            'kategori' => 'required|exists:categories,id',
            'merek' => 'required|string|min:2|max:100',
            'kondisi' => 'required|in:baik,cukup,rusak',
            'deskripsi' => 'required|string|min:10|max:1000',
            'media' => 'nullable|array|max:5',
            'media.*' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm',
                'max:20480', // 20MB
            ],
        ];
    }

    protected function messages()
    {
        return [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'province_id.required' => 'Provinsi wajib dipilih.',
            'regency_id.required' => 'Kabupaten/Kota wajib dipilih.',
            'district_id.required' => 'Kecamatan wajib dipilih.',
            'village_id.required' => 'Desa/Kelurahan wajib dipilih.',
            'address_detail.required' => 'Detail alamat wajib diisi.',
            'kategori.required' => 'Kategori barang wajib dipilih.',
            'merek.required' => 'Merek dan tipe wajib diisi.',
            'kondisi.required' => 'Kondisi barang wajib dipilih.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter.',
            'media.max' => 'Maksimal 5 file yang dapat diupload.',
            'media.*.max' => 'Ukuran file maksimal 20MB.',
        ];
    }

    public function mount()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $this->nama = $user->name;
            $this->email = $user->email ?? '';
            $this->whatsapp = $user->phone ?? '';
        }
    }

    public function removeMedia($index)
    {
        if (isset($this->media[$index])) {
            unset($this->media[$index]);
            $this->media = array_values($this->media);
        }
    }


    public function submit()
    {
        $rateLimitKey = 'submit-sell:' . request()->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($rateLimitKey);
            throw \Illuminate\Validation\ValidationException::withMessages([
                'rate_limit' => 'Anda telah mencapai batas pengajuan. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
            ]);
        }

        $this->validate();

        $kondisiMap = [
            'baik' => 'good',
            'cukup' => 'fair',
            'rusak' => 'needs_repair',
        ];
        
        // Find category ID by slug since form uses slug 'tv', 'kulkas'
        $category = Category::where('slug', $this->kategori)->first();
        $categoryId = $category ? $category->id : null;
        
        // If it's still null, try finding by id in case form was updated
        if (!$categoryId) {
            $categoryId = $this->kategori;
        }

        $submission = SellSubmission::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'customer_name' => $this->nama,
            'customer_email' => $this->email,
            'customer_phone' => $this->whatsapp,
            'customer_whatsapp' => $this->whatsapp,
            'province_id' => $this->province_id,
            'regency_id' => $this->regency_id,
            'district_id' => $this->district_id,
            'village_id' => $this->village_id,
            'address_detail' => $this->address_detail,
            'category_id' => $categoryId,
            'device_brand' => $this->merek,
            'device_model' => null, // Form doesn't separate brand and model
            'condition' => $kondisiMap[$this->kondisi],
            'description' => $this->deskripsi,
            'status' => 'pending',
        ]);

        event(new \App\Events\SellSubmissionCreated($submission));

        if (!empty($this->media)) {
            foreach ($this->media as $file) {
                $path = $file->store('sell-submissions', 'public');
                $type = str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'photo';
                SellSubmissionImage::create([
                    'sell_submission_id' => $submission->id,
                    'path' => $path,
                    'type' => $type,
                ]);
            }
        }

        // Kirim email konfirmasi pengajuan jual ke pelanggan
        if (!empty($submission->customer_email)) {
            try {
                \Illuminate\Support\Facades\Mail::to($submission->customer_email)
                    ->send(new \App\Mail\SellSubmissionConfirmationMail($submission));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Failed sending sell submission confirmation email {$submission->submission_code}: " . $e->getMessage());
            }
        }

        $this->submitted = true;
        $this->newServiceCode = $submission->submission_code;
        $this->submittedWhatsapp = $this->whatsapp;
        $this->reset(['nama', 'email', 'whatsapp', 'province_id', 'regency_id', 'district_id', 'village_id', 'address_detail', 'kategori', 'merek', 'kondisi', 'deskripsi', 'media']);
        
        \Illuminate\Support\Facades\RateLimiter::hit($rateLimitKey, 3600); // 1 jam cooldown
    }

    public function resetForm()
    {
        $this->submitted = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        return view('livewire.frontend.sell-form', compact('categories'));
    }
}
