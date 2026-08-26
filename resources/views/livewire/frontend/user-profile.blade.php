<div wire:poll.15s="refreshData" class="min-h-screen bg-[#F8FAFC] py-10 lg:py-16">
    <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-10">

        {{-- Top User Profile Card --}}
        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm p-6 sm:p-8 mb-8">
            <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-6">
                
                {{-- User Avatar & Identity --}}
                <div class="flex flex-col sm:flex-row items-center gap-5 text-center sm:text-left">
                    <div class="relative">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover border-4 border-white shadow-md ring-2 ring-gray-100" />
                        <span class="absolute bottom-1 right-1 w-5 h-5 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>

                    <div>
                        <div class="flex items-center justify-center sm:justify-start gap-2.5 mb-1">
                            <h1 class="text-2xl sm:text-3xl font-black font-public text-gray-900 tracking-tight">{{ $user->name }}</h1>
                            @if($user->hasRole('super_admin'))
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-black text-[#FFCC00] uppercase tracking-wider">Super Admin</span>
                            @elseif($user->hasRole('teknisi'))
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 uppercase tracking-wider">Teknisi</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 uppercase tracking-wider">Member</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 font-medium">{{ $user->email }} {{ $user->phone ? '• ' . $user->phone : '' }}</p>
                        <p class="text-xs text-gray-400 mt-1">Bergabung sejak {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</p>
                    </div>
                </div>

                {{-- Quick Stats & Settings Shortcut --}}
                <div class="flex items-center gap-3 w-full md:w-auto justify-center sm:justify-end">
                    <a href="{{ route('user.settings') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold text-xs sm:text-sm rounded-xl border border-gray-200 transition-all cursor-pointer">
                        <i class="fa-solid fa-gear text-gray-400"></i>
                        <span>Pengaturan Akun</span>
                    </a>
                </div>
            </div>

            {{-- Metric Highlights --}}
            <div class="grid grid-cols-3 gap-3 sm:gap-6 mt-8 pt-6 border-t border-gray-100 text-center">
                <div class="p-3 sm:p-4 rounded-2xl bg-gray-50/80 border border-gray-100">
                    <p class="text-2xl sm:text-3xl font-black font-public text-gray-900">{{ $orders->count() }}</p>
                    <p class="text-[11px] sm:text-xs font-bold text-gray-500 uppercase tracking-wider mt-0.5">Pesanan Produk</p>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl bg-gray-50/80 border border-gray-100">
                    <p class="text-2xl sm:text-3xl font-black font-public text-gray-900">{{ $services->count() }}</p>
                    <p class="text-[11px] sm:text-xs font-bold text-gray-500 uppercase tracking-wider mt-0.5">Tiket Servis</p>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl bg-gray-50/80 border border-gray-100">
                    <p class="text-2xl sm:text-3xl font-black font-public text-gray-900">{{ $sells->count() }}</p>
                    <p class="text-[11px] sm:text-xs font-bold text-gray-500 uppercase tracking-wider mt-0.5">Barang Dijual</p>
                </div>
            </div>
        </div>

        {{-- Success / Error Alerts --}}
        @if ($successMessage)
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-semibold flex items-center gap-3 animate-in fade-in">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                <span>{{ $successMessage }}</span>
            </div>
        @endif

        {{-- Navigation Pill Tabs --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 scrollbar-none border-b border-gray-200">
            <button type="button" wire:click="setTab('orders')" class="inline-flex items-center gap-2.5 px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all cursor-pointer {{ $selectedTab === 'orders' ? 'bg-black text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                <i class="fa-solid fa-bag-shopping {{ $selectedTab === 'orders' ? 'text-[#FFCC00]' : 'text-gray-400' }}"></i>
                <span>Riwayat Pesanan ({{ $orders->count() }})</span>
            </button>

            <button type="button" wire:click="setTab('services')" class="inline-flex items-center gap-2.5 px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all cursor-pointer {{ $selectedTab === 'services' ? 'bg-black text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                <i class="fa-solid fa-screwdriver-wrench {{ $selectedTab === 'services' ? 'text-[#FFCC00]' : 'text-gray-400' }}"></i>
                <span>Riwayat Servis ({{ $services->count() }})</span>
            </button>

            <button type="button" wire:click="setTab('sells')" class="inline-flex items-center gap-2.5 px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all cursor-pointer {{ $selectedTab === 'sells' ? 'bg-black text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                <i class="fa-solid fa-hand-holding-dollar {{ $selectedTab === 'sells' ? 'text-[#FFCC00]' : 'text-gray-400' }}"></i>
                <span>Jual Elektronik ({{ $sells->count() }})</span>
            </button>

            <button type="button" wire:click="setTab('profile')" class="inline-flex items-center gap-2.5 px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all cursor-pointer {{ $selectedTab === 'profile' ? 'bg-black text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                <i class="fa-solid fa-user-pen {{ $selectedTab === 'profile' ? 'text-[#FFCC00]' : 'text-gray-400' }}"></i>
                <span>Edit Biodata</span>
            </button>
        </div>

        {{-- TAB 1: RIWAYAT PESANAN PRODUK --}}
        @if ($selectedTab === 'orders')
            <div class="space-y-4">
                @forelse ($orders as $order)
                    <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm p-6 transition-all hover:border-gray-300">
                        {{-- Header Order --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-gray-100 gap-3">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-xl bg-gray-100 text-black flex items-center justify-center font-black">
                                    <i class="fa-solid fa-box text-sm"></i>
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-gray-900 font-mono text-sm sm:text-base">#{{ $order->order_code }}</h3>
                                        <span class="text-xs text-gray-400">• {{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Pengiriman: <strong class="text-gray-700">{{ $order->delivery_type === 'pickup' ? 'Ambil di Toko' : 'Kirim ke Alamat' }}</strong>
                                    </p>
                                </div>
                            </div>

                            {{-- Status Badge --}}
                            <div class="flex items-center gap-2">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-100 text-amber-900 border-amber-200',
                                        'processing' => 'bg-blue-100 text-blue-900 border-blue-200',
                                        'shipped' => 'bg-purple-100 text-purple-900 border-purple-200',
                                        'completed' => 'bg-emerald-100 text-emerald-900 border-emerald-200',
                                        'cancelled' => 'bg-rose-100 text-rose-900 border-rose-200',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Menunggu Pembayaran',
                                        'processing' => 'Sedang Diproses',
                                        'shipped' => 'Sedang Dikirim',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Order Items List --}}
                        <div class="space-y-3 mb-5">
                            @foreach ($order->orderItems as $item)
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->product->image_url ?? 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=200&h=200&fit=crop' }}" alt="{{ $item->product_name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-200 shrink-0 bg-gray-50" />
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 line-clamp-1">{{ $item->product_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 font-mono">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Footer Order (Total & Actions) --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-gray-100 gap-4">
                            <div>
                                <span class="text-xs text-gray-400 block uppercase font-bold">Total Pembayaran</span>
                                <span class="text-lg sm:text-xl font-black font-public text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                {{-- Invoice Download Button --}}
                                <a href="{{ route('order.invoice.download', $order->order_code) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-bold rounded-xl border border-gray-200 transition-all">
                                    <i class="fa-solid fa-file-invoice text-gray-400"></i>
                                    <span>Unduh Invoice (PDF)</span>
                                </a>

                                {{-- Pay Now Button if Unpaid --}}
                                @if (in_array($order->payment_status, ['unpaid', 'pending']) && $order->status !== 'cancelled')
                                    <a href="{{ route('checkout.success', $order->order_code) }}" class="inline-flex items-center gap-1.5 px-5 py-2 bg-black hover:bg-gray-900 text-[#FFCC00] text-xs font-bold rounded-xl shadow-sm transition-all">
                                        <i class="fa-solid fa-wallet"></i>
                                        <span>Bayar Sekarang</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <i class="fa-solid fa-bag-shopping text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Riwayat Pesanan</h4>
                        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">Anda belum pernah melakukan pembelian produk elektronik di Prokar.</p>
                        <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-black text-[#FFCC00] font-bold text-sm hover:bg-gray-900 transition-all">
                            <span>Mulai Belanja Elektronik</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                @endforelse
            </div>
        @endif

        {{-- TAB 2: RIWAYAT SERVIS UNIT --}}
        @if ($selectedTab === 'services')
            <div class="space-y-4">
                @forelse ($services as $service)
                    <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm p-6 transition-all hover:border-gray-300">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-gray-100 gap-3">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-black">
                                    <i class="fa-solid fa-wrench text-sm"></i>
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-gray-900 font-mono text-sm sm:text-base">#{{ $service->service_code }}</h3>
                                        <span class="text-xs text-gray-400">• {{ $service->created_at->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Perangkat: <strong class="text-gray-800">{{ $service->device_brand }} {{ $service->device_model }}</strong>
                                        ({{ $service->service_type === 'datang' ? 'Teknisi ke Rumah' : 'Bawa ke Workshop' }})
                                    </p>
                                </div>
                            </div>

                            {{-- Status Badge --}}
                            @php
                                $serviceStatusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-900 border-amber-200',
                                    'diagnosing' => 'bg-blue-100 text-blue-900 border-blue-200',
                                    'waiting_approval' => 'bg-purple-100 text-purple-900 border-purple-200',
                                    'in_progress' => 'bg-indigo-100 text-indigo-900 border-indigo-200',
                                    'completed' => 'bg-emerald-100 text-emerald-900 border-emerald-200',
                                    'cancelled' => 'bg-rose-100 text-rose-900 border-rose-200',
                                ];
                                $serviceStatusLabels = [
                                    'pending' => 'Menunggu Antrean',
                                    'diagnosing' => 'Proses Diagnosa',
                                    'waiting_approval' => 'Persetujuan Biaya',
                                    'in_progress' => 'Sedang Dikerjakan',
                                    'completed' => 'Selesai Diperbaiki',
                                    'cancelled' => 'Dibatalkan',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $serviceStatusClasses[$service->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $serviceStatusLabels[$service->status] ?? ucfirst($service->status) }}
                            </span>
                        </div>

                        {{-- Complaint & Diagnosis --}}
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 mb-4 space-y-2">
                            <div class="text-xs">
                                <strong class="text-gray-700">Keluhan Kerusakan:</strong>
                                <p class="text-gray-600 mt-0.5">{{ $service->complaint }}</p>
                            </div>
                            @if ($service->diagnosis)
                                <div class="text-xs pt-2 border-t border-gray-200/60">
                                    <strong class="text-gray-900">Hasil Diagnosa Teknisi:</strong>
                                    <p class="text-gray-700 mt-0.5">{{ $service->diagnosis }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Approval Box if waiting approval --}}
                        @if ($service->status === 'waiting_approval')
                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 mb-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold text-amber-900">Persetujuan Estimasi Biaya Perbaikan:</p>
                                    <p class="text-lg font-black text-amber-950 font-public">Rp {{ number_format($service->estimated_cost, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" wire:click="approveServiceCost({{ $service->id }})" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all cursor-pointer">
                                        Setujui Biaya
                                    </button>
                                    <button type="button" wire:click="rejectServiceCost({{ $service->id }})" class="px-4 py-2 bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 font-bold text-xs rounded-xl transition-all cursor-pointer">
                                        Tolak
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- Footer Servis --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-400 block uppercase font-bold">Biaya Akhir</span>
                                <span class="text-base sm:text-lg font-black font-public text-gray-900">
                                    {{ $service->final_cost ? 'Rp ' . number_format($service->final_cost, 0, ',', '.') : ($service->estimated_cost ? 'Rp ' . number_format($service->estimated_cost, 0, ',', '.') . ' (Estimasi)' : 'Belum Ditentukan') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                {{-- Lacak Servis Realtime Link --}}
                                <a href="{{ route('servis.lacak') }}?kode={{ $service->service_code }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-black hover:bg-gray-800 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                                    <i class="fa-solid fa-magnifying-glass-location text-[#FFCC00]"></i>
                                    <span>Lacak Servis Realtime</span>
                                </a>

                                {{-- Warranty Card PDF Download if Completed --}}
                                @if ($service->status === 'completed')
                                    <a href="{{ route('servis.garansi.download', $service->service_code) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-900 text-xs font-bold rounded-xl border border-amber-200 transition-all">
                                        <i class="fa-solid fa-shield-halved text-amber-600"></i>
                                        <span>Unduh Kartu Garansi (PDF)</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <i class="fa-solid fa-screwdriver-wrench text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Tiket Servis</h4>
                        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">Elektronik Anda bermasalah? Teknisi berpengalaman kami siap memperbaiki.</p>
                        <a href="{{ route('servis.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-black text-[#FFCC00] font-bold text-sm hover:bg-gray-900 transition-all">
                            <span>Ajukan Servis Sekarang</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                @endforelse
            </div>
        @endif

        {{-- TAB 3: JUAL ELEKTRONIK BEKAS --}}
        @if ($selectedTab === 'sells')
            <div class="space-y-4">
                @forelse ($sells as $sell)
                    <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm p-6 transition-all hover:border-gray-300">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-gray-100 gap-3">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black">
                                    <i class="fa-solid fa-tag text-sm"></i>
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-gray-900 font-mono text-sm sm:text-base">#{{ $sell->submission_code }}</h3>
                                        <span class="text-xs text-gray-400">• {{ $sell->created_at->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Unit: <strong class="text-gray-800">{{ $sell->device_brand }} {{ $sell->device_model }}</strong>
                                        (Kondisi: {{ ucfirst($sell->condition) }})
                                    </p>
                                </div>
                            </div>

                            @php
                                $sellStatusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-900 border-amber-200',
                                    'price_offered' => 'bg-blue-100 text-blue-900 border-blue-200',
                                    'scheduled' => 'bg-purple-100 text-purple-900 border-purple-200',
                                    'completed' => 'bg-emerald-100 text-emerald-900 border-emerald-200',
                                    'rejected' => 'bg-rose-100 text-rose-900 border-rose-200',
                                ];
                                $sellStatusLabels = [
                                    'pending' => 'Menunggu Penawaran',
                                    'price_offered' => 'Penawaran Diberikan',
                                    'scheduled' => 'Jadwal Penjemputan',
                                    'completed' => 'Selesai & Terbayar',
                                    'rejected' => 'Ditolak',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $sellStatusClasses[$sell->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $sellStatusLabels[$sell->status] ?? ucfirst($sell->status) }}
                            </span>
                        </div>

                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 mb-4 text-xs">
                            <strong class="text-gray-700">Deskripsi Barang:</strong>
                            <p class="text-gray-600 mt-0.5">{{ $sell->description }}</p>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-400 block uppercase font-bold">Harga Penawaran Toko</span>
                                <span class="text-base sm:text-lg font-black font-public text-emerald-600">
                                    {{ $sell->offered_price ? 'Rp ' . number_format($sell->offered_price, 0, ',', '.') : 'Sedang Dinilai' }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', setting('shop_whatsapp') ?? '089504841279') }}?text={{ urlencode('Halo Prokar Elektronik, saya ingin menanyakan pengajuan jual nomor #' . $sell->submission_code) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                    <span>Chat Admin via WA</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <i class="fa-solid fa-hand-holding-dollar text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Pengajuan Jual Barang</h4>
                        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">Punya TV, Kulkas, atau AC bekas yang tidak terpakai? Jual ke Prokar dengan penjemputan gratis.</p>
                        <a href="{{ route('jual.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-black text-[#FFCC00] font-bold text-sm hover:bg-gray-900 transition-all">
                            <span>Ajukan Jual Elektronik</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                @endforelse
            </div>
        @endif

        {{-- TAB 4: EDIT BIODATA & PROFIL --}}
        @if ($selectedTab === 'profile')
            <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm p-6 sm:p-8">
                <div class="pb-4 mb-6 border-b border-gray-100">
                    <h3 class="text-lg font-black text-gray-900 font-public">Informasi Biodata Akun</h3>
                    <p class="text-xs text-gray-500">Perbarui nama, kontak telepon WhatsApp, serta foto profil Anda.</p>
                </div>

                <form wire:submit.prevent="saveProfile" class="space-y-6 max-w-2xl">
                    {{-- Avatar Upload Section --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Foto Profil (Avatar)</label>
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-200 bg-gray-100 shrink-0">
                                @if ($avatar_file)
                                    <img src="{{ $avatar_file->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover" />
                                @else
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover" />
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" wire:model="avatar_file" accept="image/png,image/jpeg,image/webp" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer" />
                                <p class="text-[11px] text-gray-400 mt-1">PNG, JPG, WebP (Maksimal 2MB)</p>
                                @error('avatar_file') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Name --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-black focus:ring-black focus:outline-none" required />
                        @error('name') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Alamat Email</label>
                        <input type="email" wire:model="email" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-black focus:ring-black focus:outline-none" required />
                        @error('email') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Phone / WhatsApp --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nomor WhatsApp / HP</label>
                        <input type="text" wire:model="phone" placeholder="089504841279" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-black focus:ring-black focus:outline-none" />
                        <p class="text-[11px] text-gray-400 mt-1">Digunakan untuk notifikasi pesanan dan konfirmasi penjemputan servis.</p>
                        @error('phone') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-black hover:bg-gray-900 text-white font-bold text-sm rounded-xl shadow-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-check text-[#FFCC00]"></i>
                            <span>Simpan Perubahan Biodata</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</div>
