<div>
    <section aria-labelledby="formulir-heading" class="w-full">
        @if ($submitted)
            <div class="border border-neutral-200 p-8 md:p-12 bg-[#F0FFF4] text-center" style="box-shadow:0 2px 8px #0000000d;"
                x-data="{ 
                    code: '{{ $newServiceCode }}',
                    copied: false,
                    saveToLocal() {
                        let services = JSON.parse(localStorage.getItem('my_services') || '[]');
                        if(this.code && !services.includes(this.code)) {
                            services.push(this.code);
                            localStorage.setItem('my_services', JSON.stringify(services));
                        }
                    },
                    copyCode() {
                        navigator.clipboard.writeText(this.code);
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    }
                }"
                x-init="saveToLocal()"
            >
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#34C759] flex items-center justify-center">
                    <i class="fa-solid fa-check text-white text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold uppercase mb-2">Pengajuan Berhasil</h2>
                @if($serviceType === 'kirim')
                    <p class="text-[#444748] text-sm mb-4">
                        Silakan <strong>antar/kirimkan perangkat Anda ke toko kami</strong> dan tunjukkan kode tiket di bawah ini.<br>
                        Gunakan kode ini juga untuk melacak status servis Anda.
                    </p>
                @else
                    <p class="text-[#444748] text-sm mb-4">
                        <strong>Teknisi kami akan segera memproses jadwal kunjungan</strong> ke alamat Anda.<br>
                        Gunakan kode di bawah ini untuk melacak status servis Anda.
                    </p>
                @endif
                
                <div class="max-w-xs mx-auto mb-6 bg-white border border-gray-300 p-4 flex items-center justify-between">
                    <span class="text-xl font-bold tracking-widest text-black" x-text="code"></span>
                    <button @click="copyCode" class="text-gray-500 hover:text-black transition-colors" title="Salin Kode">
                        <i class="fa-regular fa-copy text-lg" x-show="!copied"></i>
                        <i class="fa-solid fa-check text-green-500 text-lg" x-show="copied" x-cloak></i>
                    </button>
                </div>

                <div class="flex flex-col gap-3 justify-center max-w-xs mx-auto">
                    <a href="{{ url('/servis/lacak') }}?code={{ $newServiceCode }}" 
                        class="bg-black text-white px-6 py-3 font-bold text-sm uppercase tracking-wider hover:bg-[#222] transition-colors block text-center">
                        Lacak Status Sekarang
                    </a>
                    <button wire:click="resetForm" type="button"
                        class="bg-white border border-gray-300 text-black px-6 py-3 font-bold text-sm uppercase tracking-wider hover:bg-gray-50 transition-colors block text-center">
                        Ajukan Servis Lain
                    </button>
                </div>
            </div>
        @else
            <!-- Riwayat Servis / Memori Kartu Servis -->
            <div x-data="{
                localCodes: [],
                authCheck: {{ auth()->check() ? 'true' : 'false' }},
                dbServices: {{ \Illuminate\Support\Js::from($userServices ?? []) }},
                init() {
                    let stored = JSON.parse(localStorage.getItem('my_services') || '[]');
                    this.localCodes = stored;
                    
                    if (this.authCheck && this.localCodes.length > 0) {
                        $wire.syncLocalCodes(this.localCodes).then(() => {});
                    }
                },
                get displayedServices() {
                    if (this.authCheck) {
                        return this.dbServices.map(s => s.service_code);
                    }
                    return this.localCodes;
                }
            }">
                <div x-show="displayedServices.length > 0" class="mb-8" x-cloak>
                    <h2 class="text-black text-lg md:text-xl font-bold uppercase mb-3">Riwayat Servis Anda</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <template x-for="code in displayedServices" :key="code">
                            <a :href="'{{ url('/servis/lacak') }}/' + code"
                                class="bg-[#FAFAFA] border border-gray-200 p-4 flex justify-between items-center hover:border-black hover:bg-white transition-colors group">
                                <span class="text-sm font-bold font-inter text-black" x-text="code"></span>
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-widest flex items-center gap-1 group-hover:text-black">
                                    Lacak <i class="fa-solid fa-arrow-right"></i>
                                </span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <div class="pb-3 md:pb-4 border-b border-gray-100 mb-6 md:mb-8">
                <h2 id="formulir-heading" class="text-black text-xl md:text-2xl font-bold uppercase">Formulir Pengajuan</h2>
            </div>

            <form wire:submit.prevent="submit" class="flex flex-col gap-6" x-data="mediaUploader()">

                <div class="flex flex-col md:flex-row gap-5 md:gap-6">
                    <div class="flex-1 flex flex-col gap-2">
                        <label for="nama" class="text-black text-xs font-bold uppercase tracking-wide">Nama Lengkap</label>
                        <input wire:model="nama" id="nama" type="text" placeholder="Masukkan nama Anda"
                            class="w-full px-4 py-3.5 bg-white border border-gray-200 text-base text-black focus:outline-none focus:border-black transition-colors" />
                        @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex-1 flex flex-col gap-2">
                        <label for="email" class="text-black text-xs font-bold uppercase tracking-wide">Alamat Email</label>
                        <input wire:model="email" id="email" type="email" placeholder="email@contoh.com"
                            class="w-full px-4 py-3.5 bg-white border border-gray-200 text-base text-black focus:outline-none focus:border-black transition-colors" />
                        @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex-1 flex flex-col gap-2">
                        <label for="whatsapp" class="text-black text-xs font-bold uppercase tracking-wide">Nomor WhatsApp</label>
                        <input wire:model="whatsapp" id="whatsapp" type="tel" placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-3.5 bg-white border border-gray-200 text-base text-black focus:outline-none focus:border-black transition-colors" />
                        @error('whatsapp') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-5 md:gap-6">
                    <div class="flex-1 flex flex-col gap-2">
                        <label for="kategori" class="text-black text-xs font-bold uppercase tracking-wide">Kategori Perangkat</label>
                        <select wire:model="kategori" id="kategori"
                            class="select-caret w-full px-4 py-3.5 bg-white border border-gray-400 text-base text-black focus:outline-none focus:border-black transition-colors">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('kategori') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex-1 flex flex-col gap-2">
                        <label for="merek" class="text-black text-xs font-bold uppercase tracking-wide">Merek &amp; Tipe</label>
                        <input wire:model="merek" id="merek" type="text" placeholder="Contoh: Samsung RT38K5032S8"
                            class="w-full px-4 py-3.5 bg-white border border-gray-200 text-base text-black focus:outline-none focus:border-black transition-colors" />
                        @error('merek') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="deskripsi" class="text-black text-xs font-bold uppercase tracking-wide">Deskripsi Keluhan</label>
                    <textarea wire:model="deskripsi" id="deskripsi" rows="5" placeholder="Jelaskan masalah yang dialami perangkat secara detail..."
                        class="w-full px-4 py-3 bg-white border border-gray-400 text-base text-black resize-none focus:outline-none focus:border-black transition-colors"></textarea>
                    @error('deskripsi') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Upload Foto/Video dengan auto-compress --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-black text-xs font-bold uppercase tracking-wide">Upload Foto/Video Kendala (Opsional, Max 5 file)</label>
                        <label for="upload-input" class="flex flex-col items-center justify-center gap-2 p-8 bg-[#FAFAFA] border border-gray-200 cursor-pointer hover:bg-gray-100 transition-colors" :class="{ 'pointer-events-none opacity-50': compressing }">
                            <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-3xl" aria-hidden="true"></i>
                            <p class="text-[#7E7576] text-sm text-center">Klik di sini untuk upload<br>(Pilih Kamera, Galeri, atau File Manager di HP Anda)</p>
                            <span class="px-4 py-2 bg-white border border-gray-200 text-black text-sm font-bold">Pilih File</span>
                            {{-- Kosongkan atribut accept agar Android memunculkan opsi Kamera --}}
                            <input x-ref="fileInput" x-on:change="handleFiles($event)" id="upload-input" type="file" multiple class="hidden" />
                        </label>
                        <p class="text-[#7E7576] text-xs mt-1">Foto max 5MB, Video otomatis dikompres sebelum diunggah.</p>
                    </div>

                    {{-- Compression Progress --}}
                    <div x-show="compressing" x-transition class="border border-yellow-300 bg-yellow-50 p-4 flex flex-col gap-2" x-cloak>
                        <div class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span class="text-sm font-bold text-yellow-800" x-text="compressionMessage"></span>
                        </div>
                        <div class="w-full bg-yellow-200 h-2">
                            <div class="bg-yellow-600 h-2 transition-all duration-300" :style="'width:' + compressionProgress + '%'"></div>
                        </div>
                        <p class="text-xs text-yellow-700" x-text="compressionProgress + '% selesai'"></p>
                    </div>

                    {{-- File Preview --}}
                    <template x-if="processedFiles.length > 0">
                        <div class="flex flex-col gap-1.5 mt-1">
                            <template x-for="(file, index) in processedFiles" :key="index">
                                <div class="flex items-center gap-2 text-xs bg-gray-50 border border-gray-200 px-3 py-2">
                                    <template x-if="file.type.startsWith('video/')">
                                        <i class="fa-solid fa-video text-blue-500"></i>
                                    </template>
                                    <template x-if="file.type.startsWith('image/')">
                                        <i class="fa-solid fa-image text-green-500"></i>
                                    </template>
                                    <span class="flex-1 truncate" x-text="file.name"></span>
                                    <span class="text-gray-400 whitespace-nowrap" x-text="formatSize(file.size)"></span>
                                    <template x-if="file._compressed">
                                        <span class="px-1.5 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold uppercase">Dikompres</span>
                                    </template>
                                    <button type="button" x-on:click="removeFile(index)" class="text-red-400 hover:text-red-600 ml-1">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>

                    <p class="text-xs text-gray-500" x-text="processedFiles.length + ' file dipilih'"></p>
                    @error('media') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    @error('media.*') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

                @if($serviceType === 'datang')
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <label class="text-black text-xs font-bold uppercase tracking-wide">Alamat Lengkap</label>
                            <span class="px-1.5 py-0.5 bg-[#FAFAFA] border border-gray-200 text-[#7E7576] text-[9px] font-bold uppercase tracking-wide">Wajib</span>
                        </div>
                        @include('livewire.frontend.address-picker', [
                            'inputClass' => 'w-full px-4 py-3 bg-white border border-gray-400 text-base text-black focus:outline-none focus:border-black transition-colors',
                            'labelClass' => 'text-black text-xs font-bold uppercase tracking-wide mb-2 block'
                        ])
                        @error('province_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('regency_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('district_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('village_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('address_detail') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif

                <button type="submit"
                    class="px-8 py-4 bg-black text-white text-sm font-bold uppercase tracking-wide hover:bg-[#222] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="compressing">
                    <span wire:loading.remove wire:target="submit">Ajukan Servis Sekarang</span>
                    <span wire:loading wire:target="submit"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...</span>
                </button>
            </form>
        @endif
    </section>

    @script
    <script>
    Alpine.data('mediaUploader', () => ({
        compressing: false,
        compressionProgress: 0,
        compressionMessage: '',
        processedFiles: [],

        formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        },

        removeFile(index) {
            this.processedFiles.splice(index, 1);
            this.uploadToLivewire();
        },

        async handleFiles(event) {
            const newFiles = Array.from(event.target.files);
            if (newFiles.length === 0) return;

            // Enforce max 5 files total
            const totalAllowed = 5 - this.processedFiles.length;
            const filesToProcess = newFiles.slice(0, totalAllowed);

            if (newFiles.length > totalAllowed) {
                alert('Maksimal 5 file. ' + (newFiles.length - totalAllowed) + ' file diabaikan.');
            }

            for (let i = 0; i < filesToProcess.length; i++) {
                const file = filesToProcess[i];

                if (file.type.startsWith('video/')) {
                    // Compress video
                    this.compressing = true;
                    this.compressionMessage = 'Mengompres video "' + file.name + '" (' + (i + 1) + '/' + filesToProcess.length + ')...';
                    this.compressionProgress = 0;

                    try {
                        const compressed = await this.compressVideo(file, (progress) => {
                            this.compressionProgress = Math.round(progress * 100);
                        });
                        compressed._compressed = true;
                        this.processedFiles.push(compressed);
                    } catch (err) {
                        console.warn('Video compression failed, using original:', err);
                        // Fallback: upload original
                        file._compressed = false;
                        this.processedFiles.push(file);
                    }
                } else {
                    // Images pass through directly
                    file._compressed = false;
                    this.processedFiles.push(file);
                }
            }

            this.compressing = false;
            this.compressionProgress = 0;

            // Reset file input so same file can be selected again
            event.target.value = '';

            // Upload all processed files to Livewire
            this.uploadToLivewire();
        },

        uploadToLivewire() {
            if (this.processedFiles.length === 0) {
                this.$wire.set('media', []);
                return;
            }
            this.$wire.uploadMultiple('media', this.processedFiles,
                () => { /* success */ },
                (error) => { console.error('Upload error:', error); },
                (event) => { /* progress */ }
            );
        },

        compressVideo(file, onProgress) {
            return new Promise((resolve, reject) => {
                const video = document.createElement('video');
                video.src = URL.createObjectURL(file);
                video.muted = true; // required for autoplay
                video.playsInline = true;
                video.preload = 'auto';

                video.onerror = () => {
                    URL.revokeObjectURL(video.src);
                    reject(new Error('Failed to load video'));
                };

                video.onloadedmetadata = () => {
                    // Calculate target dimensions (max 480p height)
                    const maxHeight = 480;
                    let width = video.videoWidth;
                    let height = video.videoHeight;

                    if (height > maxHeight) {
                        const ratio = maxHeight / height;
                        width = Math.round(width * ratio);
                        height = maxHeight;
                    }

                    // Ensure even dimensions (codec requirement)
                    width = width % 2 === 0 ? width : width + 1;
                    height = height % 2 === 0 ? height : height + 1;

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');

                    // Get canvas video stream (24 fps)
                    const canvasStream = canvas.captureStream(24);

                    // Try to get audio from the video
                    let combinedStream = canvasStream;
                    let audioContext = null;

                    try {
                        audioContext = new AudioContext();
                        const source = audioContext.createMediaElementSource(video);
                        const dest = audioContext.createMediaStreamDestination();
                        source.connect(dest);
                        // Don't connect to speakers — silent processing

                        combinedStream = new MediaStream([
                            ...canvasStream.getVideoTracks(),
                            ...dest.stream.getAudioTracks(),
                        ]);
                    } catch (e) {
                        // Audio extraction failed — continue without audio
                        console.warn('Audio extraction skipped:', e);
                    }

                    // Determine MIME type — browser support varies
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
                        videoBitsPerSecond: 1_000_000, // 1 Mbps
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

                    // Start recording and play video
                    recorder.start(100); // collect data every 100ms
                    video.muted = false; // unmute for audio capture
                    video.volume = 0; // but keep silent
                    video.play().catch(() => {
                        // Autoplay blocked — try muted
                        video.muted = true;
                        video.play();
                    });

                    // Draw frames to canvas
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

                    // Stop when video ends
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
</div>
