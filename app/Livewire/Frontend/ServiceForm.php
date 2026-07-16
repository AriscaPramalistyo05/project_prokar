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
    
    public $serviceType = 'datang'; // datang = home_visit, kirim = drop_off

    // Address Fields
    public $province_id = '';
    public $regency_id = '';
    public $district_id = '';
    public $village_id = '';
    public $address_detail = '';
    public $province_name = '';
    public $regency_name = '';
    public $district_name = '';
    public $village_name = '';

    public $media = [];
    public $submitted = false;
    public $newServiceCode = '';
    public $userServices = [];

    #[Livewire\Attributes\On('serviceTypeChanged')]
    public function setServiceType($type)
    {
        $this->serviceType = $type;
    }

    public function mount()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $this->nama = $user->name;
            $this->email = $user->email ?? '';
            $this->whatsapp = $user->phone ?? '';

            $this->userServices = \App\Models\ServiceOrder::where('user_id', $user->id)
                ->latest()
                ->get()
                ->toArray();
        } else {
            $this->userServices = [];
        }
    }

    #[Livewire\Attributes\On('sync-local-codes')]
    public function syncLocalCodes($codes)
    {
        if (\Illuminate\Support\Facades\Auth::check() && is_array($codes) && count($codes) > 0) {
            $userId = \Illuminate\Support\Facades\Auth::id();
            
            // Sync all codes that belong to this session but don't have a user_id yet
            \App\Models\ServiceOrder::whereIn('service_code', $codes)
                ->whereNull('user_id')
                ->update(['user_id' => $userId]);
        }
    }

    protected function rules()
    {
        return [
            'nama' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:150',
            'whatsapp' => ['required', 'string', new \App\Rules\IndonesianPhone()],
            'kategori' => 'required|exists:categories,id',
            'merek' => 'required|string|max:100',
            'deskripsi' => 'required|string|min:10|max:1000',
            'province_id' => $this->serviceType === 'datang' ? 'required' : 'nullable',
            'regency_id' => $this->serviceType === 'datang' ? 'required' : 'nullable',
            'district_id' => $this->serviceType === 'datang' ? 'required' : 'nullable',
            'village_id' => $this->serviceType === 'datang' ? 'required' : 'nullable',
            'address_detail' => $this->serviceType === 'datang' ? 'required|string|min:10' : 'nullable|string|min:10',
            'media' => 'nullable|array|max:5',
            'media.*' => [
                'required',
                'file',
                'max:20480', // 20MB
                function ($attribute, $value, $fail) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    $imageExts = ['jpg', 'jpeg', 'png', 'webp'];
                    $videoExts = ['mp4', 'mov', 'avi', 'webm'];
                    if (!in_array($extension, array_merge($imageExts, $videoExts))) {
                        $fail('File harus berupa foto (jpg, png, webp) atau video (mp4, mov, avi, webm).');
                    }
                },
            ],
        ];
    }

    protected function messages()
    {
        return [
            'province_id.required' => 'Provinsi wajib dipilih.',
            'regency_id.required' => 'Kabupaten/Kota wajib dipilih.',
            'district_id.required' => 'Kecamatan wajib dipilih.',
            'village_id.required' => 'Desa/Kelurahan wajib dipilih.',
            'address_detail.required' => 'Detail alamat wajib diisi.',
            'media.max' => 'Maksimal 5 file yang dapat diupload.',
            'media.*.max' => 'Ukuran file maksimal 20MB.',
        ];
    }

    public function submit()
    {
        $this->validate();

        \Illuminate\Support\Facades\DB::transaction(function () {
            $serviceOrder = \App\Models\ServiceOrder::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(), // null if guest
                'customer_name' => $this->nama,
                'customer_email' => $this->email,
                'customer_phone' => $this->whatsapp,
                'service_type' => $this->serviceType === 'datang' ? 'home_visit' : 'drop_off',
                'category_id' => $this->kategori,
                'device_brand' => $this->merek,
                'complaint' => $this->deskripsi,
                'province_id' => $this->serviceType === 'datang' ? $this->province_id : null,
                'regency_id' => $this->serviceType === 'datang' ? $this->regency_id : null,
                'district_id' => $this->serviceType === 'datang' ? $this->district_id : null,
                'village_id' => $this->serviceType === 'datang' ? $this->village_id : null,
                'address_detail' => $this->serviceType === 'datang' ? $this->address_detail : null,
                'status' => 'pending',
            ]);

            if (!empty($this->media)) {
                foreach ($this->media as $file) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $videoExts = ['mp4', 'mov', 'avi', 'webm'];
                    $mediaType = in_array($extension, $videoExts) ? 'video' : 'image';

                    $serviceOrder->serviceImages()->create([
                        'path' => $file->store('service_images', 'public'),
                        'type' => 'complaint',
                        'media_type' => $mediaType,
                        'uploaded_by' => \Illuminate\Support\Facades\Auth::id(), // null if guest
                    ]);
                }
            }

            // Fire event so FCM can notify admins
            event(new \App\Events\ServiceOrderCreated($serviceOrder));

            $this->newServiceCode = $serviceOrder->service_code;
        });

        $this->submitted = true;
        $this->reset(['nama', 'email', 'whatsapp', 'kategori', 'merek', 'deskripsi', 'province_id', 'regency_id', 'district_id', 'village_id', 'address_detail', 'media']);
    }

    public function resetForm()
    {
        $this->submitted = false;
        $this->newServiceCode = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('livewire.frontend.service-form', compact('categories'));
    }
}
