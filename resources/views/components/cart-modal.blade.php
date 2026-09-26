<script>
(function() {
    function cartModalState() {
        return {
            isOpen: false,
            loading: false,
            errorMessage: null,
            quantity: 1,
            item: {
                id: null,
                name: '',
                price: '',
                img: '',
                stock: 10
            },
            open(data) {
                this.item = {
                    id: data?.id || null,
                    name: data?.name || '',
                    price: data?.price || '',
                    img: data?.img || '',
                    stock: parseInt(data?.stock) || 10
                };
                this.quantity = 1;
                this.errorMessage = null;
                this.loading = false;
                this.isOpen = true;
                if (window.lenis) {
                    try { window.lenis.stop(); } catch(e){}
                }
            },
            close() {
                this.isOpen = false;
                if (window.lenis) {
                    try { window.lenis.start(); } catch(e){}
                }
            },
            increment() {
                if (this.quantity < (this.item.stock || 99)) {
                    this.quantity++;
                }
            },
            decrement() {
                if (this.quantity > 1) {
                    this.quantity--;
                }
            },
            async submitAddToCart() {
                if (!this.item.id) {
                    this.errorMessage = 'Data produk tidak valid.';
                    return;
                }
                this.loading = true;
                this.errorMessage = null;

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                    const response = await fetch('{{ route("cart.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            product_id: this.item.id,
                            quantity: this.quantity
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.close();
                        if (window.Livewire) {
                            window.Livewire.dispatch('cart-updated', { count: data.count || data.cart_count });
                        }
                        window.dispatchEvent(new CustomEvent('cart-count-updated', {
                            detail: { count: data.count || data.cart_count }
                        }));
                        window.dispatchEvent(new CustomEvent('cart-updated', {
                            detail: { count: data.count || data.cart_count }
                        }));
                        if (window.showToast) {
                            window.showToast('Produk berhasil ditambahkan ke keranjang!', 'success');
                        }
                    } else {
                        this.errorMessage = data.message || 'Gagal menambahkan produk ke keranjang.';
                    }
                } catch (err) {
                    this.errorMessage = 'Terjadi kesalahan jaringan. Coba lagi.';
                } finally {
                    this.loading = false;
                }
            }
        };
    }

    window.cartModalState = cartModalState;

    if (window.Alpine) {
        window.Alpine.data('cartModalState', cartModalState);
    } else {
        document.addEventListener('alpine:init', function() {
            window.Alpine.data('cartModalState', cartModalState);
        });
    }

    window.openCartModal = function(param) {
        let data = {};
        if (param instanceof HTMLElement) {
            data = {
                id: param.dataset.productId || param.dataset.id || null,
                name: param.dataset.productName || param.dataset.name || '',
                price: param.dataset.productPrice || param.dataset.price || '',
                img: param.dataset.productImg || param.dataset.img || '',
                stock: param.dataset.productStock || param.dataset.stock || 10
            };
        } else if (typeof param === 'object' && param !== null) {
            data = param;
        }
        window.dispatchEvent(new CustomEvent('open-cart-modal', { detail: data }));
    };
})();
</script>

<div x-data="cartModalState()"
     @open-cart-modal.window="open($event.detail)"
     x-cloak>

    {{-- Teleport modal to document body to prevent layout/stacking glitches --}}
    <template x-teleport="body">
        <div x-show="isOpen"
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-[100000] flex items-end sm:items-center sm:justify-center p-0 sm:p-4">

            {{-- Backdrop --}}
            <div x-show="isOpen"
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="close()"
                 class="fixed inset-0 bg-black/60 backdrop-blur-xs -z-10"></div>

            {{-- Modal Dialog --}}
            <div x-show="isOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-full opacity-0 sm:translate-y-4 sm:scale-95"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-y-0 opacity-100 sm:translate-y-0 sm:scale-100"
                 x-transition:leave-end="translate-y-full opacity-0 sm:translate-y-4 sm:scale-95"
                 @click.stop
                 class="w-full sm:max-w-md bg-white rounded-t-[1.75rem] sm:rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh] transition-all">

                {{-- Drag Handle Indicator (Mobile) --}}
                <div class="pt-3 pb-1 flex justify-center sm:hidden">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>

                {{-- Content Area --}}
                <div class="p-5 sm:p-6 flex flex-col flex-1 overflow-y-auto">

                    {{-- Product Header: Thumbnail, Price, Stock, Close Button --}}
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-100 relative">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-[#f8fafc] border border-gray-200/80 p-2 shrink-0 flex items-center justify-center overflow-hidden">
                            <img :src="item.img"
                                 :alt="item.name"
                                 class="w-full h-full object-contain"
                                 onerror="this.src='https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&q=80'">
                        </div>

                        <div class="flex-1 pr-8 min-w-0">
                            <p x-text="item.price" class="text-xl sm:text-2xl font-black font-public text-black tracking-tight mb-1"></p>
                            <p class="text-xs sm:text-sm font-medium text-gray-500 font-inter mb-1">
                                Stok: <span x-text="item.stock > 0 ? item.stock : 'Tersedia'" class="font-bold text-gray-700"></span>
                            </p>
                            <h3 x-text="item.name" class="text-xs sm:text-sm font-medium font-public text-gray-600 line-clamp-1"></h3>
                        </div>

                        {{-- Close Button --}}
                        <button type="button"
                                @click="close()"
                                class="absolute top-0 right-0 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-black flex items-center justify-center transition-colors cursor-pointer"
                                aria-label="Tutup">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    {{-- Quantity Stepper Section --}}
                    <div class="py-5 flex items-center justify-between">
                        <span class="font-bold font-public text-gray-900 text-base">Jumlah</span>

                        <div class="inline-flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                            <button type="button"
                                    @click="decrement()"
                                    :disabled="quantity <= 1"
                                    class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-black transition-colors disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer">
                                <i class="fa-solid fa-minus text-xs"></i>
                            </button>

                            <input type="text"
                                   readonly
                                   :value="quantity"
                                   class="w-12 h-10 text-center font-bold font-inter text-gray-900 bg-transparent text-sm border-x border-gray-200 focus:outline-none select-none">

                            <button type="button"
                                    @click="increment()"
                                    :disabled="quantity >= (item.stock || 99)"
                                    class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-black transition-colors disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Error Message --}}
                    <div x-show="errorMessage" x-cloak style="display: none;" class="mb-3 p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-xs font-semibold">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i>
                        <span x-text="errorMessage"></span>
                    </div>

                    {{-- Bottom Action Button --}}
                    <div class="pt-2 mt-auto">
                        <button type="button"
                                @click="submitAddToCart()"
                                :disabled="loading"
                                class="w-full bg-[#eb4d2d] sm:bg-brand-yellow hover:bg-[#d93f1f] sm:hover:bg-yellow-400 text-white sm:text-black font-black font-public text-base uppercase py-3.5 px-6 rounded-2xl transition-all shadow-md active:scale-[0.99] flex items-center justify-center gap-2 tracking-wide disabled:opacity-50 cursor-pointer">
                            <span x-show="!loading" class="flex items-center gap-2">
                                <i class="fa-solid fa-cart-plus text-lg"></i>
                                <span>Masukkan Keranjang</span>
                            </span>
                            <span x-show="loading" style="display: none;" class="flex items-center gap-2">
                                <i class="fa-solid fa-spinner fa-spin text-lg"></i>
                                <span>Menambahkan...</span>
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </template>
</div>
