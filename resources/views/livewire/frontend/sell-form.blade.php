<section id="form-penjualan" aria-labelledby="form-heading" class="section-overlap bg-white pt-20 pb-48 md:pb-64 z-30 relative">
    <div class="max-w-4xl mx-auto px-6 lg:px-12">
        <div class="bg-white border border-gray-200 rounded-[2.5rem] p-6 sm:p-10 md:p-14 pb-14 sm:pb-20 md:pb-24 shadow-card relative overflow-hidden">

            <div wire:key="form-content" class="relative z-10 transition-opacity duration-500" :class="{ 'opacity-40 pointer-events-none': $wire.submitted }">
                <div class="text-center mb-10">
                    <h2 id="form-heading" class="text-3xl md:text-4xl font-black font-public uppercase tracking-tighter text-black mb-2">Form Penjualan</h2>
                    <p class="text-gray-500 font-inter text-sm md:text-base">Silakan isi data dengan lengkap agar kami dapat memberikan estimasi yang akurat.</p>
                </div>

                <form wire:submit.prevent="submit" class="flex flex-col gap-6">
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-sm">
                            <p class="font-bold mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation text-red-500"></i> Mohon periksa kembali isian Anda:
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
                            <label for="nama" class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Nama Lengkap</label>
                            <input wire:model="nama" id="nama" type="text" placeholder="Masukkan nama Anda"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" />
                            @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="whatsapp" class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Nomor WhatsApp</label>
                            <input wire:model="whatsapp" id="whatsapp" type="tel" placeholder="Contoh: 08123456789"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" />
                            @error('whatsapp') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Alamat Lengkap</label>
                        @include('partials.address-picker', [
                            'province_id'  => $province_id,
                            'regency_id'   => $regency_id,
                            'district_id'  => $district_id,
                            'village_id'   => $village_id,
                            'address_detail' => $address_detail,
                            'inputClass'   => 'w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all',
                            'labelClass'   => 'block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public',
                        ])
                        @error('province_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('regency_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('district_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('village_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('address_detail') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kategori" class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Kategori Barang</label>
                            <div class="select-wrap">
                                <select wire:model="kategori" id="kategori"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all appearance-none cursor-pointer">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('kategori') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="merek" class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Merek &amp; Tipe</label>
                            <input wire:model="merek" id="merek" type="text" placeholder="Contoh: LG Smart TV 43 Inch / Kulkas Sharp 2 Pintu"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all" />
                            @error('merek') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <fieldset>
                        <legend class="block text-sm font-bold uppercase tracking-widest mb-3 text-gray-700 font-public">Kondisi Fisik &amp; Mesin</legend>
                        <div class="flex flex-wrap gap-4 md:gap-8 bg-gray-50 border border-gray-200 rounded-2xl p-4 md:p-5" role="radiogroup" aria-label="Kondisi barang">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center">
                                    <input wire:model="kondisi" type="radio" value="baik" class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded-full checked:border-black transition-all" />
                                    <div class="absolute w-2.5 h-2.5 bg-black rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                                </div>
                                <span class="text-base text-gray-700 group-hover:text-black font-semibold transition-colors">Baik / Normal</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center">
                                    <input wire:model="kondisi" type="radio" value="cukup" class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded-full checked:border-black transition-all" />
                                    <div class="absolute w-2.5 h-2.5 bg-black rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                                </div>
                                <span class="text-base text-gray-700 group-hover:text-black font-semibold transition-colors">Minus / Lecet</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center">
                                    <input wire:model="kondisi" type="radio" value="rusak" class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded-full checked:border-black transition-all" />
                                    <div class="absolute w-2.5 h-2.5 bg-black rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                                </div>
                                <span class="text-base text-gray-700 group-hover:text-black font-semibold transition-colors">Rusak / Mati Total</span>
                            </label>
                        </div>
                        @error('kondisi') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </fieldset>

                    <div>
                        <label for="deskripsi" class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Detail / Kelengkapan Tambahan</label>
                        <textarea wire:model="deskripsi" id="deskripsi" rows="3"
                            placeholder="Jelaskan secara spesifik (Contoh: Remote hilang, dus box ada, dingin normal, dll)"
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-base focus:bg-white focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all resize-none"></textarea>
                        @error('deskripsi') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="mediaUploader">
                        <label class="block text-sm font-bold uppercase tracking-widest mb-2 text-gray-700 font-public">Upload Foto/Video (Maks 5 File)</label>

                        <div class="border-2 border-dashed border-gray-300 hover:border-black rounded-2xl p-6 text-center transition-colors bg-white cursor-pointer relative"
                            x-on:click="$refs.fileInput.click()">
                            <input x-ref="fileInput" type="file" multiple accept="image/*,video/*" class="hidden"
                                x-on:change="handleFiles($event)" />

                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-black">
                                <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                            </div>
                            <h4 class="font-public font-bold text-base text-black mb-1">Klik untuk mengunggah foto/video</h4>
                            <p class="text-xs text-gray-400 font-inter mb-4">Foto max 5MB, Video otomatis dikompres sebelum diunggah</p>
                            <span class="inline-block bg-brand-yellow text-black font-public font-bold uppercase text-xs tracking-wider px-6 py-2.5 rounded-full">Pilih File</span>
                        </div>

                        {{-- Progress Bar Kompresi --}}
                        <div x-show="compressing" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-2xl space-y-2" x-cloak>
                            <div class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-yellow-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span class="text-sm font-bold text-yellow-800 font-public break-all" x-text="compressionMessage"></span>
                            </div>
                            <div class="w-full bg-yellow-200 h-2 rounded-full overflow-hidden">
                                <div class="bg-yellow-600 h-2 transition-all duration-300" :style="'width:' + compressionProgress + '%'"></div>
                            </div>
                            <p class="text-xs text-yellow-700 font-inter" x-text="compressionProgress + '% selesai'"></p>
                        </div>

                        {{-- File Preview --}}
                        <template x-if="processedFiles.length > 0">
                            <div class="flex flex-col gap-2.5 mt-3 mb-2 max-h-64 overflow-y-auto p-1.5 border border-gray-100 rounded-2xl bg-gray-50/50">
                                <template x-for="(file, index) in processedFiles" :key="index">
                                    <div class="flex items-center gap-3 text-xs bg-white border border-gray-200 rounded-2xl px-4 py-3 font-inter shadow-sm">
                                        <template x-if="file.type.startsWith('video/')">
                                            <i class="fa-solid fa-video text-blue-500 text-base"></i>
                                        </template>
                                        <template x-if="file.type.startsWith('image/')">
                                            <i class="fa-solid fa-image text-green-500 text-base"></i>
                                        </template>
                                        <span class="flex-1 truncate font-medium text-black" x-text="file.name"></span>
                                        <span class="text-gray-400 whitespace-nowrap" x-text="formatSize(file.size)"></span>
                                        <template x-if="file._compressed">
                                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded">Dikompres</span>
                                        </template>
                                        <button type="button" x-on:click="removeFile(index)" class="text-red-400 hover:text-red-600 ml-1 p-1">
                                            <i class="fa-solid fa-xmark text-sm"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <div class="mt-8 mb-4 sm:mb-6">
                            <button type="submit"
                                class="w-full bg-black text-brand-yellow py-5 rounded-full font-public font-black uppercase text-lg tracking-widest hover:bg-gray-800 transition-colors btn-hover flex justify-center items-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed shadow-xl active:scale-[0.99]"
                                :disabled="compressing || uploading">
                                <span wire:loading.remove wire:target="submit" x-show="!uploading" class="flex items-center gap-2">
                                    Kirim Penawaran <i class="fa-solid fa-paper-plane"></i>
                                </span>
                                <span x-show="uploading" class="flex items-center gap-2" x-cloak>
                                    <i class="fa-solid fa-cloud-arrow-up fa-bounce"></i> Mengunggah file...
                                </span>
                                <span wire:loading wire:target="submit" class="flex items-center gap-2">
                                    <i class="fa-solid fa-spinner fa-spin"></i> Memproses...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if ($submitted)
        <!-- Full Section Success Overlay -->
        <div wire:key="success-overlay" x-data="{ 
                code: '{{ $newServiceCode }}',
                copied: false,
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
            }"
            x-init="initConfetti();"
            class="absolute inset-0 z-50 bg-black/60 backdrop-blur-sm flex flex-col items-center justify-end pb-[11rem] md:pb-[15.5rem] px-4 sm:px-6"
        >
            <div class="bg-white rounded-[2.5rem] p-6 sm:p-12 shadow-2xl max-w-lg w-full text-center relative border border-emerald-100 overflow-hidden transform transition-all mt-auto mb-0">
                <!-- Decorative green glow -->
                <div class="absolute -top-16 left-1/2 -translate-x-1/2 w-48 h-48 bg-emerald-400/20 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Success Animated Check Icon -->
                <div class="w-20 h-20 mx-auto mb-5 rounded-full bg-gradient-to-tr from-emerald-500 to-green-400 flex items-center justify-center shadow-lg shadow-emerald-500/30 text-white relative">
                    <i class="fa-solid fa-check text-3xl"></i>
                </div>

                <h2 class="text-3xl sm:text-4xl font-black font-public uppercase tracking-tighter text-black mb-2">Penawaran Terkirim!</h2>
                <p class="text-gray-600 font-inter text-sm sm:text-base mb-6 leading-relaxed">
                    Tim kami akan menghubungi Anda dalam 1x24 jam melalui WhatsApp.<br>
                    Simpan kode penawaran Anda di bawah ini:
                </p>

                <!-- Ticket Code Box -->
                <div class="bg-gray-50 border-2 border-emerald-500/20 rounded-2xl p-4 flex items-center justify-between mb-6 shadow-inner relative group">
                    <div class="text-left">
                        <span class="block text-[10px] uppercase font-bold tracking-widest text-emerald-600 font-public mb-0.5">Kode Penawaran Jual</span>
                        <span class="text-xl sm:text-2xl font-black font-public tracking-widest text-black" x-text="code"></span>
                    </div>
                    <button @click="copyCode" type="button" class="bg-white border border-gray-200 hover:border-black text-black px-4 py-2 rounded-xl font-bold font-public text-xs uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-sm active:scale-95 cursor-pointer">
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
                    $waText = "Kode penawaran jual saya di Prokar Elektronik:\n*" . $newServiceCode . "*\n\nAdmin akan menghubungi nomor ini dalam waktu 1x24 jam.";
                    $waUrl = "https://wa.me/" . $waNumber . "?text=" . urlencode($waText);
                @endphp

                <div class="flex flex-col gap-3">
                    <a href="{{ $waUrl }}" target="_blank"
                        class="bg-[#25D366] text-white px-6 py-4 rounded-full font-bold font-public text-sm uppercase tracking-wider hover:bg-[#128C7E] transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fa-brands fa-whatsapp text-lg"></i> Simpan ke WA Saya
                    </a>
                    <button wire:click="resetForm" type="button"
                        class="bg-black text-brand-yellow px-6 py-4 rounded-full font-bold font-public text-sm uppercase tracking-wider hover:bg-gray-800 transition-all block w-full text-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5 cursor-pointer">
                        Kirim Penawaran Lain
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

    refreshGSAP() {
        if (typeof ScrollTrigger !== 'undefined') {
            setTimeout(() => {
                ScrollTrigger.refresh();
            }, 150);
        }
    },

    formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    },

    removeFile(index) {
        this.processedFiles.splice(index, 1);
        this.$wire.removeMedia(index).then(() => {
            this.refreshGSAP();
        });
    },

    async handleFiles(event) {
        const newFiles = Array.from(event.target.files);
        if (newFiles.length === 0) return;

        const totalAllowed = 5 - this.processedFiles.length;
        const filesToProcess = newFiles.slice(0, totalAllowed);

        if (newFiles.length > totalAllowed) {
            alert('Maksimal 5 file. ' + (newFiles.length - totalAllowed) + ' file diabaikan.');
        }

        const newFilesToUpload = [];

        for (let i = 0; i < filesToProcess.length; i++) {
            const file = filesToProcess[i];

            if (file.type.startsWith('video/')) {
                this.compressing = true;
                this.compressionMessage = 'Mengompres video "' + file.name + '" (' + (i + 1) + '/' + filesToProcess.length + ')...';
                this.compressionProgress = 0;
                this.refreshGSAP();

                try {
                    const compressed = await this.compressVideo(file, (progress) => {
                        this.compressionProgress = Math.round(progress * 100);
                    });
                    compressed._compressed = true;
                    this.processedFiles.push(compressed);
                    newFilesToUpload.push(compressed);
                } catch (err) {
                    console.warn('Video compression failed, using original:', err);
                    file._compressed = false;
                    this.processedFiles.push(file);
                    newFilesToUpload.push(file);
                }
            } else {
                file._compressed = false;
                this.processedFiles.push(file);
                newFilesToUpload.push(file);
            }
        }

        this.compressing = false;
        this.compressionProgress = 0;
        this.refreshGSAP();
        event.target.value = '';
        if (newFilesToUpload.length > 0) {
            this.uploadToLivewire(newFilesToUpload);
        }
        this.refreshGSAP();
    },

    uploadToLivewire(filesToUpload) {
        if (!filesToUpload || filesToUpload.length === 0) return;
        this.uploading = true;
        this.$wire.uploadMultiple('media', filesToUpload,
            () => { this.uploading = false; this.refreshGSAP(); },
            (error) => { console.error('Upload error:', error); this.uploading = false; this.refreshGSAP(); },
            (event) => { /* progress */ }
        );
        this.refreshGSAP();
    },

    compressVideo(file, onProgress) {
        return new Promise((resolve, reject) => {
            const video = document.createElement('video');
            video.src = URL.createObjectURL(file);
            video.muted = true;
            video.playsInline = true;
            video.preload = 'auto';

            video.onerror = () => {
                URL.revokeObjectURL(video.src);
                reject(new Error('Failed to load video'));
            };

            video.onloadedmetadata = () => {
                const maxHeight = 480;
                let width = video.videoWidth;
                let height = video.videoHeight;

                if (height > maxHeight) {
                    const ratio = maxHeight / height;
                    width = Math.round(width * ratio);
                    height = maxHeight;
                }

                width = width % 2 === 0 ? width : width + 1;
                height = height % 2 === 0 ? height : height + 1;

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');

                const canvasStream = canvas.captureStream(24);
                let combinedStream = canvasStream;
                let audioContext = null;

                try {
                    audioContext = new AudioContext();
                    const source = audioContext.createMediaElementSource(video);
                    const dest = audioContext.createMediaStreamDestination();
                    source.connect(dest);

                    combinedStream = new MediaStream([
                        ...canvasStream.getVideoTracks(),
                        ...dest.stream.getAudioTracks(),
                    ]);
                } catch (e) {
                    console.warn('Audio extraction skipped:', e);
                }

                const mimeType = MediaRecorder.isTypeSupported('video/webm;codecs=vp8')
                    ? 'video/webm;codecs=vp8'
                    : (MediaRecorder.isTypeSupported('video/webm') ? 'video/webm' : '');

                if (!mimeType) {
                    URL.revokeObjectURL(video.src);
                    if (audioContext) audioContext.close();
                    reject(new Error('Browser does not support WebM recording'));
                    return;
                }

                const recorder = new MediaRecorder(combinedStream, {
                    mimeType: mimeType,
                    videoBitsPerSecond: 1_000_000,
                });

                const chunks = [];
                recorder.ondataavailable = (e) => {
                    if (e.data.size > 0) chunks.push(e.data);
                };

                recorder.onstop = () => {
                    const blob = new Blob(chunks, { type: 'video/webm' });
                    const compressedName = file.name.replace(/\.[^.]+$/, '.webm');
                    const compressedFile = new File([blob], compressedName, {
                        type: 'video/webm',
                        lastModified: Date.now(),
                    });

                    URL.revokeObjectURL(video.src);
                    if (audioContext) audioContext.close();
                    resolve(compressedFile);
                };

                recorder.onerror = (e) => {
                    URL.revokeObjectURL(video.src);
                    if (audioContext) audioContext.close();
                    reject(e.error || new Error('Recording error'));
                };

                recorder.start(100);
                video.muted = false;
                video.volume = 0;
                video.play().catch(() => {
                    video.muted = true;
                    video.play();
                });

                function drawFrame() {
                    if (video.ended || video.paused) {
                        recorder.stop();
                        return;
                    }
                    ctx.drawImage(video, 0, 0, width, height);
                    if (onProgress && video.duration) {
                        onProgress(Math.min(video.currentTime / video.duration, 1));
                    }
                    requestAnimationFrame(drawFrame);
                }
                drawFrame();

                video.onended = () => {
                    if (recorder.state !== 'inactive') {
                        recorder.stop();
                    }
                };
            };
        });
    },
}));
</script>
@endscript
