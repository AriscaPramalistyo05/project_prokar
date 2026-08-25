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
    
    // Delivery Type: 'delivery' (Dikirim ke Alamat) or 'pickup' (Ambil di Toko)
    public string $deliveryType = 'delivery';

    // Payment Option: 'midtrans', 'cash_store', 'cod', 'dp'
    public string $paymentOption = 'midtrans';
    public int $customDpAmount = 0;

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
        $rules = [
            'name' => 'required|string|min:1|max:120',
            'phone' => ['required', 'string', new \App\Rules\IndonesianPhone()],
            'email' => 'required|email|max:120',
            'deliveryType' => 'required|in:delivery,pickup',
            'paymentOption' => 'required|in:midtrans,cash_store,cod,dp',
        ];

        // If delivery type is 'delivery', validate full address fields
        if ($this->deliveryType === 'delivery') {
            $rules['province_id'] = 'required';
            $rules['regency_id'] = 'required';
            $rules['district_id'] = 'required';
            $rules['village_id'] = 'required';
            $rules['postal_code'] = 'required|string|min:4|max:10';
            $rules['address_detail'] = 'required|string|min:5';
        }

        return $rules;
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

    public function updatedDeliveryType($value)
    {
        if ($value === 'pickup') {
            $this->shippingCost = 0;
            $this->shippingCourier = 'Ambil Sendiri di Toko Prokar';
            $this->paymentOption = 'cash_store';
        } else {
            $this->paymentOption = 'midtrans';
        }
        $this->dispatch('delivery-type-changed', type: $value);
        $this->dispatch('payment-option-changed', option: $this->paymentOption);
    }

    public function updatedPaymentOption($value)
    {
        $this->dispatch('payment-option-changed', option: $value);
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
        if ($this->deliveryType === 'pickup') {
            $this->shippingCost = 0;
            $this->shippingCourier = 'Ambil Sendiri di Toko Prokar';
            return;
        }

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

        // 1. Validasi ulang & hitung subtotal murni dari Database
        $subtotal = 0;
        $verifiedItems = [];

        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->status !== 'available' || $product->stock < 1) {
                $this->addError('cart', "Produk {$item['name']} saat ini tidak tersedia atau stok habis.");
                return;
            }

            $actualUnitPrice = ($product->is_promo && $product->promo_price) 
                ? (int) $product->promo_price 
                : (int) $product->price;

            $itemQty = max(1, min((int) $product->stock, (int) ($item['quantity'] ?? 1)));
            $itemSubtotal = $actualUnitPrice * $itemQty;
            $subtotal += $itemSubtotal;

            $verifiedItems[] = [
                'id' => $product->id,
                'name' => $product->name,
                'unit_price' => $actualUnitPrice,
                'quantity' => $itemQty,
                'subtotal' => $itemSubtotal,
            ];
        }

        if ($subtotal <= 0 || empty($verifiedItems)) {
            $this->addError('cart', 'Total belanja tidak valid atau produk kosong.');
            return;
        }

        // 2. Ongkos Kirim
        if ($this->deliveryType === 'pickup') {
            $shippingCost = 0;
            $shippingCourierName = 'Ambil Sendiri di Toko Prokar';
        } else {
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
        }

        $total = max(1, $subtotal + $shippingCost);

        // 3. Kalkulasi DP & Sisa Pelunasan
        $paymentType = 'full';
        $downPayment = 0;
        $remainingPayment = 0;
        $paymentMethod = 'midtrans';

        if ($this->paymentOption === 'dp') {
            $paymentType = 'down_payment';
            $downPayment = (int) round($total * 0.5); // DP 50%
            $remainingPayment = $total - $downPayment;
            $paymentMethod = 'midtrans_dp';
        } elseif ($this->paymentOption === 'cash_store') {
            $paymentMethod = 'cash_store';
        } elseif ($this->paymentOption === 'cod') {
            $paymentMethod = 'cod';
        }

        // 4. Buat Record Order di Database
        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $this->name,
            'customer_email' => $this->email,
            'customer_phone' => $this->phone,
            'delivery_type' => $this->deliveryType,
            'address_detail' => $this->deliveryType === 'pickup' ? 'Ambil di Toko Prokar Elektronik Jepara' : $this->address_detail,
            'province_id' => $this->deliveryType === 'pickup' ? '33' : $this->province_id,
            'regency_id' => $this->deliveryType === 'pickup' ? '3320' : $this->regency_id,
            'district_id' => $this->deliveryType === 'pickup' ? '3320070' : $this->district_id,
            'village_id' => $this->deliveryType === 'pickup' ? '3320070002' : $this->village_id,
            'postal_code' => $this->deliveryType === 'pickup' ? '59452' : $this->postal_code,
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'status' => 'pending',
            'payment_type' => $paymentType,
            'down_payment' => $downPayment,
            'remaining_payment' => $remainingPayment,
            'payment_method' => $paymentMethod,
            'payment_status' => 'unpaid',
        ]);

        // 5. Buat OrderItem untuk setiap produk
        $midtransItems = [];
        foreach ($verifiedItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'product_price' => (int) $item['unit_price'],
                'quantity' => (int) $item['quantity'],
                'subtotal' => (int) $item['subtotal'],
            ]);

            $midtransItems[] = [
                'id' => 'PROD-' . $item['id'],
                'price' => (int) $item['unit_price'],
                'quantity' => (int) $item['quantity'],
                'name' => mb_strimwidth($item['name'], 0, 45, '...'),
            ];
        }

        if ($shippingCost > 0) {
            $midtransItems[] = [
                'id' => 'SHIPPING',
                'price' => (int) $shippingCost,
                'quantity' => 1,
                'name' => mb_strimwidth('Ongkir: ' . $shippingCourierName, 0, 45, '...'),
            ];
        }

        // Kosongkan keranjang belanja
        $cartService->clear();

        // 6. Tangani Pembayaran Non-Midtrans (Cash di Kasir & COD)
        if (in_array($this->paymentOption, ['cash_store', 'cod'])) {
            $this->submitted = true;
            $this->orderCode = $order->order_code;

            session()->flash('success_order', [
                'order_code' => $order->order_code,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'delivery_type' => $this->deliveryType,
            ]);

            $this->redirect(route('home'));
            return;
        }

        // 7. Generate Midtrans Snap Token (Full atau DP)
        $chargeAmount = $this->paymentOption === 'dp' ? (int) $downPayment : (int) $total;

        // Jika DP, buat item list ringkas sesuai nominal DP
        if ($this->paymentOption === 'dp') {
            $midtransItems = [
                [
                    'id' => 'DP-50',
                    'price' => $chargeAmount,
                    'quantity' => 1,
                    'name' => 'Uang Muka / DP 50% (' . $order->order_code . ')',
                ]
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => $chargeAmount,
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
