<?php

namespace App\Livewire\Frontend;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use App\Services\MidtransService;
use App\Services\ShippingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckoutAddressForm extends Component
{
    public string $name = '';
    
    // Address Fields
    public $province_id = '';
    public $regency_id = '';
    public $district_id = '';
    public $village_id = '';
    public $postal_code = '';
    public $address_detail = '';

    public string $phone = '';
    public string $email = '';
    public bool $submitted = false;

    public ?string $snapToken = null;
    public ?string $orderCode = null;

    public int $shippingCost = 0;
    public string $shippingCourier = '';

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:1|max:120',
            'province_id' => 'required',
            'regency_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
            'postal_code' => 'required|string|min:4|max:10',
            'address_detail' => 'required|string|min:5',
            'phone' => ['required', 'string', new \App\Rules\IndonesianPhone()],
            'email' => 'required|email|max:120',
        ];
    }

    protected $messages = [
        'name.required' => 'Nama lengkap wajib diisi.',
        'province_id.required' => 'Provinsi wajib dipilih.',
        'regency_id.required' => 'Kabupaten/Kota wajib dipilih.',
        'district_id.required' => 'Kecamatan wajib dipilih.',
        'village_id.required' => 'Desa/Kelurahan wajib dipilih.',
        'postal_code.required' => 'Kode pos wajib diisi.',
        'address_detail.required' => 'Detail alamat wajib diisi.',
        'phone.required' => 'Nomor telepon wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
    ];

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name ?? '';
            $this->phone = $user->phone ?? '';
            $this->email = $user->email ?? '';
        }
    }

    #[\Livewire\Attributes\On('address-updated')]
    public function updateAddress(
        $city = null,
        $regency_name = null,
        $regency_id = null,
        $province_name = null,
        $province_id = null,
        $district_name = null,
        $district_id = null,
        $village_id = null,
        $postal_code = null,
        $address_detail = null
    ): void {
        if (is_array($city)) {
            $this->province_id = $city['province_id'] ?? $this->province_id;
            $this->regency_id = $city['regency_id'] ?? $this->regency_id;
            $this->district_id = $city['district_id'] ?? $this->district_id;
            $this->village_id = $city['village_id'] ?? $this->village_id;
            $this->postal_code = $city['postal_code'] ?? $this->postal_code;
            $this->address_detail = $city['address_detail'] ?? $this->address_detail;
        } else {
            if ($province_id) $this->province_id = $province_id;
            if ($regency_id) $this->regency_id = $regency_id;
            if ($district_id) $this->district_id = $district_id;
            if ($village_id) $this->village_id = $village_id;
            if ($postal_code) $this->postal_code = $postal_code;
            if ($address_detail) $this->address_detail = $address_detail;
        }
    }

    #[\Livewire\Attributes\On('shipping-cost-changed')]
    public function updateShippingCostData($data = null, $cost = null, $courier = null, $service = null, $label = null): void
    {
        if (is_array($data)) {
            $this->shippingCost = (int) ($data['cost'] ?? $this->shippingCost);
            $this->shippingCourier = $data['label'] ?? $data['courier'] ?? $this->shippingCourier;
        } else {
            if ($cost !== null) $this->shippingCost = (int) $cost;
            if ($label || $courier) $this->shippingCourier = $label ?: $courier;
        }
    }

    public function submit(MidtransService $midtransService, ShippingService $shippingService): void
    {
        $this->validate();

        $cartService = app(CartService::class);
        $cartItems = $cartService->getItems();

        if (empty($cartItems)) {
            $this->addError('cart', 'Keranjang belanja kosong. Silakan tambahkan produk terlebih dahulu.');
            return;
        }

        // 1. Hitung subtotal produk
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ((int) $item['unit_price']) * ((int) $item['quantity']);
        }

        // 2. Verifikasi dan pastikan nominal ongkir akurat
        $targetCity = $this->regency_id ?: $this->address_detail;
        $isLocal = $shippingService->isLocalArea((string) $targetCity);

        if ($isLocal) {
            $shippingCost = 50000;
            $shippingCourierName = 'Kurir Toko Prokar (Flat Rp 50.000)';
        } else {
            if ($this->shippingCost > 0) {
                $shippingCost = $this->shippingCost;
                $shippingCourierName = !empty($this->shippingCourier) ? $this->shippingCourier : 'Ekspedisi Kargo';
            } else {
                // Kalkulasi langsung jika belum terupdate dari event
                $calc = $shippingService->calculateShipping($targetCity, $cartItems, (string) $this->postal_code);
                if (!empty($calc['options'])) {
                    $shippingCost = (int) $calc['options'][0]['cost'];
                    $shippingCourierName = $calc['options'][0]['label'] ?? 'Ekspedisi Kargo';
                } else {
                    $this->addError('shipping', 'Ongkos kirim belum berhasil dihitung. Pastikan kode pos sudah valid.');
                    return;
                }
            }
        }

        $total = $subtotal + $shippingCost;

        // 3. Buat Record Order di Database
        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $this->name,
            'customer_email' => $this->email,
            'customer_phone' => $this->phone,
            'address_detail' => $this->address_detail,
            'province_id' => $this->province_id,
            'regency_id' => $this->regency_id,
            'district_id' => $this->district_id,
            'village_id' => $this->village_id,
            'postal_code' => $this->postal_code,
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // 4. Buat OrderItem untuk setiap produk & siapkan item Midtrans
        $midtransItems = [];
        foreach ($cartItems as $item) {
            $itemSubtotal = ((int) $item['unit_price']) * ((int) $item['quantity']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'product_price' => (int) $item['unit_price'],
                'quantity' => (int) $item['quantity'],
                'subtotal' => $itemSubtotal,
            ]);

            $midtransItems[] = [
                'id' => 'PROD-' . $item['id'],
                'price' => (int) $item['unit_price'],
                'quantity' => (int) $item['quantity'],
                'name' => mb_strimwidth($item['name'], 0, 45, '...'),
            ];
        }

        // Tambahkan item ongkos kirim ke rincian Midtrans
        $midtransItems[] = [
            'id' => 'SHIPPING',
            'price' => (int) $shippingCost,
            'quantity' => 1,
            'name' => mb_strimwidth('Ongkir: ' . $shippingCourierName, 0, 45, '...'),
        ];

        // 5. Generate Midtrans Snap Token
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ],
            'item_details' => $midtransItems,
        ];

        try {
            $this->snapToken = $midtransService->getSnapToken($params);
            $order->update(['midtrans_token' => $this->snapToken]);
            $this->orderCode = $order->order_code;
            $this->submitted = true;

            $this->dispatch('pay-midtrans', [
                'snap_token' => $this->snapToken,
                'order_code' => $this->orderCode,
            ]);

        } catch (\Throwable $e) {
            $this->addError('payment', 'Gagal menghubungkan ke payment gateway: ' . $e->getMessage());
        }
    }

    public function formatRupiah(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.');
    }

    public function render()
    {
        return view('livewire.frontend.checkout-address-form');
    }
}
