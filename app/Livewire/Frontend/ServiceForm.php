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

    public $media = [];
    public $submitted = false;
    public $newServiceCode = '';

    protected $listeners = ['serviceTypeChanged' => 'setServiceType', 'address-updated' => 'updateAddress'];

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
        }
    }

    public function updateAddress($data)
    {
        $this->province_id = $data['province_id'];
        $this->regency_id = $data['regency_id'];
        $this->district_id = $data['district_id'];
        $this->village_id = $data['village_id'];
        $this->address_detail = $data['address_detail'];
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
                'customer_address' => $this->address_detail,
                'customer_city' => $this->regency_id, // we might want to resolve name later, but keeping as regency_id for now
                'status' => 'pending',
            ]);

            if (!empty($this->media)) {
                foreach ($this->media as $file) {
                    $serviceOrder->serviceImages()->create([
                        'path' => $file->store('service_images', 'public'),
                        'type' => 'complaint',
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
