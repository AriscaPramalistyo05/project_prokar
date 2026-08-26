<section id="form-servis" aria-labelledby="formulir-heading"
    class="section-overlap bg-white pt-20 pb-24 md:pt-28 md:pb-36 z-40 relative">
    <div class="max-w-4xl mx-auto px-6 lg:px-12">
        <div class="bg-white border border-gray-200 rounded-3xl p-5 sm:p-8 md:p-14 shadow-card relative overflow-hidden">



            <div wire:key="form-content" class="relative z-10 transition-opacity duration-500"
                :class="{ 'opacity-40 pointer-events-none': $wire.submitted }">
                <!-- Riwayat Servis / Memori Kartu Servis -->
                <div x-data="{
                    localCodes: [],
                    authCheck: {{ auth()->check() ? 'true' : 'false' }},
                    dbServices: $wire.entangle('userServices'),
                    init() {
                        let stored = JSON.parse(localStorage.getItem('my_services') || '[]');
                        this.localCodes = stored;
                
                        if (this.authCheck && this.localCodes.length > 0) {
                            $wire.syncLocalCodes(this.localCodes).then(() => {});
                        }
                
                        window.addEventListener('service-history-updated', (e) => {
                            this.localCodes = e.detail;
                        });
                    },
                    get displayedServices() {
                        if (this.authCheck && this.dbServices && this.dbServices.length > 0) {
                            return this.dbServices.map(s => s.service_code);
                        }
                        return this.localCodes;
                    }
                }">
                    <div x-show="displayedServices.length > 0" class="mb-10 pb-8 border-b border-gray-200" x-cloak>
                        <h3 class="text-black text-lg md:text-xl font-bold font-public uppercase mb-4">Riwayat Servis
                            Anda</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="code in displayedServices" :key="code">
                                <a :href="'{{ url('/servis/lacak') }}/' + code"
                                    class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex justify-between items-center hover:border-black hover:bg-white transition-all group shadow-sm">
                                    <span class="text-base font-bold font-public text-black" x-text="code"></span>
                                    <span
                                        class="text-xs text-gray-500 font-bold uppercase tracking-widest flex items-center gap-1 group-hover:text-black font-public">
                                        Lacak <i class="fa-solid fa-arrow-right"></i>
                                    </span>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-10">
                    <h2 id="formulir-heading"
                        class="text-3xl md:text-4xl font-black font-public uppercase tracking-tighter text-black mb-2">
                        Form Pengajuan Servis</h2>
                    <p class="text-gray-500 font-inter text-sm md:text-base">Silakan isi data dengan lengkap agar
                        teknisi kami dapat memahami kendala perangkat Anda.</p>
                </div>

                <form wire:submit.prevent="submit" class="flex flex-col gap-6" x-data="mediaUploader()">
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-sm font-inter">
                            <p class="font-bold mb-1 flex items-center gap-2 font-public">
                                <i class="fa-solid fa-circle-exclamation text-red-500"></i> Mohon periksa kembali isian
                                Anda:
                            </p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama"
                                class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Nama
                                Lengkap</label>
                            <input wire:model="nama" id="nama" type="text" placeholder="Masukkan nama Anda"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" />
                            @error('nama')
                                <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="whatsapp"
                                class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Nomor
                                WhatsApp</label>
                            <input wire:model="whatsapp" id="whatsapp" type="tel" placeholder="Contoh: 08123456789"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" />
                            @error('whatsapp')
                                <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email"
                            class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Alamat
                            Email</label>
                        <input wire:model="email" id="email" type="email" placeholder="email@contoh.com"
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" />
                        @error('email')
                            <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kategori"
                                class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Kategori
                                Perangkat</label>
                            <div class="select-wrap">
                                <select wire:model="kategori" id="kategori"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all cursor-pointer">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('kategori')
                                <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="merek"
                                class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Merek
                                &amp; Tipe</label>
                            <input wire:model="merek" id="merek" type="text"
                                placeholder="Contoh: LG Smart TV 43 Inch / Kulkas Sharp 2 Pintu"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" />
                            @error('merek')
                                <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="deskripsi"
                            class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Deskripsi
                            Keluhan</label>
                        <textarea wire:model="deskripsi" id="deskripsi" rows="4"
                            placeholder="Jelaskan masalah yang dialami secara spesifik (Contoh: Kulkas kurang dingin, TV mati total, dll)"
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all resize-none"></textarea>
                        @error('deskripsi')
                            <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($serviceType === 'datang')
                        <div id="alamat-container">
                            <label
                                class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Alamat
                                Kunjungan <span class="text-red-500 text-xs ml-1">*Wajib untuk Layanan Teknisi
                                    Datang</span></label>
                            @include('partials.address-picker', [
                                'province_id' => $province_id,
                                'regency_id' => $regency_id,
                                'district_id' => $district_id,
                                'village_id' => $village_id,
                                'address_detail' => $address_detail,
                                'inputClass' =>
                                    'w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all',
                                'labelClass' =>
                                    'block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public',
                            ])
                            @error('province_id')
                                <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                            @enderror
                            @error('regency_id')
                                <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                            @enderror
                            @error('district_id')
                                <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                            @enderror
                            @error('village_id')
                                <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                            @enderror
                            @error('address_detail')
                                <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    {{-- Upload Foto/Video dengan auto-compress --}}
                    <div>
                        <label
                            class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Upload
                            Foto/Video Kendala (Opsional, Max 5 file)</label>
                        <label for="upload-input"
                            class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-[2rem] p-10 cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-black transition-all group"
                            :class="{ 'pointer-events-none opacity-50': compressing }">
                            <div
                                class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-cloud-arrow-up text-2xl text-black"></i>
                            </div>
                            <p class="text-black font-bold text-base mb-1 font-public">Klik untuk mengunggah foto/video
                            </p>
                            <p class="text-gray-500 text-sm font-inter">Foto max 5MB, Video otomatis dikompres sebelum
                                diunggah</p>
                            <span
                                class="px-5 py-2.5 bg-black text-white text-xs font-bold font-public uppercase tracking-widest rounded-full mt-4 group-hover:bg-brand-yellow group-hover:text-black transition-colors">Pilih
                                File</span>
                            <input x-ref="fileInput" x-on:change="handleFiles($event)" id="upload-input" type="file"
                                multiple class="hidden" />
                        </label>
                        @error('media')
                            <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                        @enderror
                        @error('media.*')
                            <p class="text-red-600 text-xs mt-1 font-inter">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Compression Progress --}}
                    <div x-show="compressing" x-transition
                        class="border border-yellow-300 bg-yellow-50 p-4 rounded-2xl flex flex-col gap-2 mb-4" x-cloak>
                        <div class="flex items-start gap-2">
                            <svg class="animate-spin h-4 w-4 mt-1 flex-shrink-0 text-yellow-600"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span class="text-sm font-bold text-yellow-800 font-public break-all"
                                x-text="compressionMessage"></span>
                        </div>
                        <div class="w-full bg-yellow-200 h-2 rounded-full overflow-hidden mt-1">
                            <div class="bg-yellow-600 h-2 transition-all duration-300"
                                :style="'width:' + compressionProgress + '%'"></div>
                        </div>
                        <p class="text-xs text-yellow-700 font-inter" x-text="compressionProgress + '% selesai'"></p>
                    </div>

                    {{-- File Preview --}}
                    <template x-if="processedFiles.length > 0">
                        <div
                            class="flex flex-col gap-2.5 mt-3 mb-2 max-h-64 overflow-y-auto p-1.5 border border-gray-100 rounded-2xl bg-gray-50/50">
                            <template x-for="(file, index) in processedFiles" :key="index">
                                <div
                                    class="flex items-center gap-3 text-xs bg-white border border-gray-200 rounded-2xl px-4 py-3 font-inter shadow-sm">
                                    <template x-if="file.type.startsWith('video/')">
                                        <i class="fa-solid fa-video text-blue-500 text-base"></i>
                                    </template>
                                    <template x-if="file.type.startsWith('image/')">
                                        <i class="fa-solid fa-image text-green-500 text-base"></i>
                                    </template>
                                    <span class="flex-1 truncate font-medium text-black" x-text="file.name"></span>
                                    <span class="text-gray-400 whitespace-nowrap"
                                        x-text="formatSize(file.size)"></span>
                                    <template x-if="file._compressed">
                                        <span
                                            class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded">Dikompres</span>
                                    </template>
                                    <button type="button" x-on:click="removeFile(index)"
                                        class="text-red-400 hover:text-red-600 ml-1 p-1">
                                        <i class="fa-solid fa-xmark text-sm"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>

                    <button type="submit"
                        class="mt-6 mb-10 sm:mb-14 w-full bg-black text-white py-4 sm:py-5 rounded-2xl font-public font-black uppercase text-base sm:text-lg tracking-widest hover:bg-gray-800 transition-colors btn-hover flex justify-center items-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="compressing || uploading">
                        <span wire:loading.remove wire:target="submit" x-show="!uploading"
                            class="flex items-center gap-3">
                            Kirim Pengajuan <i class="fa-solid fa-paper-plane text-brand-yellow"></i>
                        </span>
                        <span x-show="uploading" class="flex items-center gap-2" x-cloak>
                            <i class="fa-solid fa-cloud-arrow-up fa-bounce text-brand-yellow"></i> Mengunggah file...
                        </span>
                        <span wire:loading wire:target="submit" class="flex items-center gap-2">
                            <i class="fa-solid fa-spinner fa-spin"></i> Memproses...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @if ($submitted)
        <!-- Full Section Success Overlay -->
        <div wire:key="success-overlay" x-data="{
            code: '{{ $newServiceCode }}',
            copied: false,
            saveToLocal() {
                let services = JSON.parse(localStorage.getItem('my_services') || '[]');
                if (this.code && !services.includes(this.code)) {
                    services.unshift(this.code);
                    localStorage.setItem('my_services', JSON.stringify(services));
                    window.dispatchEvent(new CustomEvent('service-history-updated', { detail: services }));
                }
            },
            copyCode() {
                navigator.clipboard.writeText(this.code);
                this.copied = true;
                setTimeout(() => this.copied = false, 2500);
            },
            initConfetti() {
                if (typeof confetti === 'function') {
                    confetti({ particleCount: 100, spread: 80, origin: { y: 0.8 } });
                } else {
                    let script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.4/dist/confetti.browser.min.js';
                    script.onload = () => confetti({ particleCount: 100, spread: 80, origin: { y: 0.8 } });
                    document.head.appendChild(script);
                }
            }
        }" x-init="saveToLocal();
        initConfetti();"
            class="absolute inset-0 z-50 bg-black/60 backdrop-blur-sm flex flex-col items-center justify-end pb-[11rem] md:pb-[15.5rem] px-4 sm:px-6">
            <div
                class="bg-white rounded-[2.5rem] p-6 sm:p-12 shadow-2xl max-w-lg w-full text-center relative border border-emerald-100 overflow-hidden transform transition-all mt-auto mb-0">
                <!-- Decorative green glow -->
                <div
                    class="absolute -top-16 left-1/2 -translate-x-1/2 w-48 h-48 bg-emerald-400/20 rounded-full blur-3xl pointer-events-none">
                </div>

                <!-- Success Animated Check Icon -->
                <div
                    class="w-20 h-20 mx-auto mb-5 rounded-full bg-gradient-to-tr from-emerald-500 to-green-400 flex items-center justify-center shadow-lg shadow-emerald-500/30 text-white relative">
                    <i class="fa-solid fa-check text-3xl"></i>
                </div>

                <h2 class="text-3xl sm:text-4xl font-black font-public uppercase tracking-tighter text-black mb-2">
                    Pengajuan Berhasil!</h2>

                @if ($serviceType === 'kirim')
                    <p class="text-gray-600 font-inter text-sm sm:text-base mb-6 leading-relaxed">
                        Silakan <strong>antar/kirimkan perangkat Anda ke toko kami</strong> dan tunjukkan kode tiket di
                        bawah ini.<br>
                        Gunakan kode ini juga untuk melacak status servis Anda.
                    </p>
                @else
                    <p class="text-gray-600 font-inter text-sm sm:text-base mb-6 leading-relaxed">
                        <strong>Teknisi kami akan segera memproses jadwal kunjungan</strong> ke alamat Anda.<br>
                        Gunakan kode di bawah ini untuk melacak status servis Anda.
                    </p>
                @endif

                <!-- Ticket Code Box -->
                <div
                    class="bg-gray-50 border-2 border-emerald-500/20 rounded-2xl p-4 flex items-center justify-between mb-6 shadow-inner relative group">
                    <div class="text-left">
                        <span
                            class="block text-[10px] uppercase font-bold tracking-widest text-emerald-600 font-public mb-0.5">Kode
                            Tiket Servis</span>
                        <span class="text-xl sm:text-2xl font-black font-public tracking-widest text-black"
                            x-text="code"></span>
                    </div>
                    <button @click="copyCode" type="button"
                        class="bg-white border border-gray-200 hover:border-black text-black px-4 py-2 rounded-xl font-bold font-public text-xs uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-sm active:scale-95 cursor-pointer">
                        <template x-if="!copied">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-regular fa-copy"></i> Salin
                            </span>
                        </template>
                        <template x-if="copied">
                            <span class="flex items-center gap-1.5 text-emerald-600">
                                <i class="fa-solid fa-check"></i> Tersalin!
                            </span>
                        </template>
                    </button>
                </div>

                @php
                    $waNumber = preg_replace('/\D/', '', $submittedWhatsapp);
                    if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                    }
                    $waText =
                        "Kode servis saya di Prokar Elektronik:\n*" .
                        $newServiceCode .
                        "*\n\nLink lacak: " .
                        url('/servis/lacak?code=' . $newServiceCode);
                    $waUrl = 'https://wa.me/' . $waNumber . '?text=' . urlencode($waText);
                @endphp

                <div class="flex flex-col gap-3">
                    <a href="{{ $waUrl }}" target="_blank"
                        class="bg-[#25D366] text-white px-6 py-4 rounded-full font-bold font-public text-sm uppercase tracking-wider hover:bg-[#128C7E] transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fa-brands fa-whatsapp text-lg"></i> Simpan ke WA Saya
                    </a>
                    <a href="{{ url('/servis/lacak') }}?code={{ $newServiceCode }}"
                        class="bg-black text-brand-yellow px-6 py-4 rounded-full font-bold font-public text-sm uppercase tracking-wider hover:bg-gray-800 transition-all block text-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Lacak Status Sekarang
                    </a>
                    <button wire:click="resetForm" type="button"
                        class="bg-gray-100 text-gray-700 hover:text-black hover:bg-gray-200 px-6 py-3.5 rounded-full font-bold font-public text-xs uppercase tracking-wider transition-colors block w-full text-center cursor-pointer">
                        Ajukan Servis Lain
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>

@script
    <script>
        Alpine.data('mediaUploader', () => ({
            compressing: false,
            uploading: false,
            compressionProgress: 0,
            compressionMessage: '',
            processedFiles: [],

            init() {
                this.$watch('$wire.submitted', (val) => {
                    if (val) {
                        this.processedFiles = [];
                        this.refreshGSAP();
                    }
                });
            },

            formatSize(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(1) + ' MB';
            },

            refreshGSAP() {
                setTimeout(() => {
                    if (typeof ScrollTrigger !== 'undefined') {
                        ScrollTrigger.refresh();
                    }
                }, 150);
            },

            removeFile(index) {
                this.processedFiles.splice(index, 1);
                $wire.removeMedia(index).then(() => {
                    this.refreshGSAP();
                });
            },

            async handleFiles(event) {
                const files = Array.from(event.target.files);
                if (!files.length) return;

                const newFilesToUpload = [];

                for (const file of files) {
                    if (this.processedFiles.length >= 5) {
                        alert('Maksimal 5 file yang dapat diunggah.');
                        break;
                    }

                    if (file.type.startsWith('video/')) {
                        this.compressing = true;
                        this.compressionMessage = `Mengompres video ${file.name}...`;
                        this.compressionProgress = 0;
                        this.refreshGSAP();

                        try {
                            const compressedBlob = await this.compressVideo(file, (progress) => {
                                this.compressionProgress = Math.round(progress);
                            });
                            const compressedFile = new File([compressedBlob], file.name.replace(/\.[^/.]+$/,
                                "") + ".mp4", {
                                type: 'video/mp4',
                                lastModified: Date.now()
                            });
                            compressedFile._compressed = true;
                            this.processedFiles.push(compressedFile);
                            newFilesToUpload.push(compressedFile);
                        } catch (e) {
                            console.warn('Kompresi video gagal, menggunakan file asli:', e);
                            this.processedFiles.push(file);
                            newFilesToUpload.push(file);
                        } finally {
                            this.compressing = false;
                            this.refreshGSAP();
                        }
                    } else if (file.type.startsWith('image/')) {
                        if (file.size > 5 * 1024 * 1024) {
                            alert(`Foto ${file.name} melebihi 5MB.`);
                            continue;
                        }
                        this.processedFiles.push(file);
                        newFilesToUpload.push(file);
                    } else {
                        alert(`Format file ${file.name} tidak didukung.`);
                    }
                }

                if (newFilesToUpload.length > 0) {
                    this.syncToLivewire(newFilesToUpload);
                }
                event.target.value = '';
            },

            syncToLivewire(filesToUpload) {
                this.uploading = true;
                $wire.uploadMultiple('media', filesToUpload,
                    () => {
                        this.uploading = false;
                        this.refreshGSAP();
                    },
                    (error) => {
                        console.error('Upload error:', error);
                        this.uploading = false;
                        this.refreshGSAP();
                    }
                );
                this.refreshGSAP();
            },

            async compressVideo(file, progressCallback) {
                return new Promise((resolve, reject) => {
                    const video = document.createElement('video');
                    video.preload = 'metadata';
                    video.muted = true;
                    video.playsInline = true;
                    video.src = URL.createObjectURL(file);

                    video.onloadedmetadata = () => {
                        URL.revokeObjectURL(video.src);
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        let width = video.videoWidth;
                        let height = video.videoHeight;
                        const maxDim = 720;
                        if (width > maxDim || height > maxDim) {
                            if (width > height) {
                                height = Math.round((height * maxDim) / width);
                                width = maxDim;
                            } else {
                                width = Math.round((width * maxDim) / height);
                                height = maxDim;
                            }
                        }

                        canvas.width = width;
                        canvas.height = height;

                        const stream = canvas.captureStream(24);
                        let recorder;
                        try {
                            recorder = new MediaRecorder(stream, {
                                mimeType: 'video/webm;codecs=vp8',
                                videoBitsPerSecond: 1000000
                            });
                        } catch (e) {
                            try {
                                recorder = new MediaRecorder(stream, {
                                    videoBitsPerSecond: 1000000
                                });
                            } catch (err) {
                                return reject(err);
                            }
                        }

                        const chunks = [];
                        recorder.ondataavailable = (e) => {
                            if (e.data.size > 0) chunks.push(e.data);
                        };
                        recorder.onstop = () => {
                            const blob = new Blob(chunks, {
                                type: 'video/mp4'
                            });
                            resolve(blob);
                        };

                        recorder.start(100);
                        video.currentTime = 0;
                        video.play();

                        const duration = video.duration || 1;
                        const updateProgress = () => {
                            if (!video.paused && !video.ended) {
                                const prog = Math.min(99, (video.currentTime / duration) *
                                    100);
                                progressCallback(prog);
                                requestAnimationFrame(updateProgress);
                            }
                        };
                        updateProgress();

                        video.onended = () => {
                            progressCallback(100);
                            recorder.stop();
                        };

                        const drawFrame = () => {
                            if (!video.paused && !video.ended) {
                                ctx.drawImage(video, 0, 0, width, height);
                                requestAnimationFrame(drawFrame);
                            }
                        };
                        drawFrame();
                    };

                    video.onerror = (e) => reject(e);
                });
            }
        }));
    </script>
@endscript
