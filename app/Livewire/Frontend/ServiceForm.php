<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\WithFileUploads;

class ServiceForm extends Component
{
    use WithFileUploads;

    public $nama = '';
    public $email = '';
    public $whatsapp = '';
    public $kategori = '';
    public $merek = '';
    public $deskripsi = '';
    public $alamat = '';
    public $kota = '';
    public $photos = [];
    public $submitted = false;
    public $serviceType = 'datang'; // datang = home_visit, kirim = drop_off
    public $serviceCode = '';

    protected $listeners = ['serviceTypeChanged' => 'setServiceType'];

    public function setServiceType($type)
    {
        $this->serviceType = $type;
    }

    protected function rules()
    {
        return [
            'nama' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:150',
            'whatsapp' => 'required|string|min:8|max:20',
            'kategori' => 'required|exists:categories,id',
            'merek' => 'required|string|max:100', // device_brand
            'deskripsi' => 'required|string|min:10|max:1000', // complaint
            'alamat' => $this->serviceType === 'datang' ? 'required|string|min:10|max:500' : 'nullable|string|max:500',
            'kota' => $this->serviceType === 'datang' ? 'required|string|max:100' : 'nullable|string|max:100',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function submit()
    {
        $this->validate();

        // Start Transaction
        \Illuminate\Support\Facades\DB::transaction(function () {
            // Create ServiceOrder
            $order = \App\Models\ServiceOrder::create([
                'customer_name' => $this->nama,
                'customer_email' => $this->email,
                'customer_phone' => $this->whatsapp,
                'service_type' => $this->serviceType === 'datang' ? 'home_visit' : 'drop_off',
                'customer_address' => $this->alamat,
                'customer_city' => $this->kota,
                'category_id' => $this->kategori,
                'device_brand' => $this->merek,
                'complaint' => $this->deskripsi,
            ]);

            $this->serviceCode = $order->service_code;

            // Handle photos
            if (!empty($this->photos)) {
                foreach ($this->photos as $photo) {
                    $path = $photo->store('services', 'public');
                    \App\Models\ServiceImage::create([
                        'service_order_id' => $order->id,
                        'path' => $path,
                        'type' => 'complaint',
                        'uploaded_by' => auth()->id(), // nullable if guest
                    ]);
                }
            }

            // Fire event so FCM can notify admins
            event(new \App\Events\ServiceOrderCreated($order));

            // Send Email Confirmation
            try {
                $this->setupSmtp();
                \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\ServiceConfirmationMail($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send service email: ' . $e->getMessage());
            }
        });

        $this->submitted = true;
        $this->reset(['nama', 'email', 'whatsapp', 'kategori', 'merek', 'deskripsi', 'alamat', 'kota', 'photos']);
    }

    private function setupSmtp()
    {
        if (setting('smtp_host')) {
            config([
                'mail.mailers.smtp.host' => setting('smtp_host'),
                'mail.mailers.smtp.port' => setting('smtp_port'),
                'mail.mailers.smtp.encryption' => setting('smtp_encryption'),
                'mail.mailers.smtp.username' => setting('smtp_username'),
                'mail.mailers.smtp.password' => setting('smtp_password'),
                'mail.from.address' => setting('smtp_from_address', config('mail.from.address')),
                'mail.from.name' => setting('smtp_from_name', config('mail.from.name')),
            ]);
            
            // Purge the mailer to force recreation with new config
            \Illuminate\Support\Facades\Mail::purge('smtp');
        }
    }

    public function resetForm()
    {
        $this->submitted = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('livewire.frontend.service-form', compact('categories'));
    }
}
