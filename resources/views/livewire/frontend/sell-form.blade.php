<div>
    @if ($submitted)
        <section id="form-penjualan" aria-labelledby="form-heading" class="w-full py-12 px-4 md:px-6 bg-[#F8F8F8]">
            <div class="max-w-4xl mx-auto border border-neutral-200 p-8 md:p-12 bg-white text-center" style="box-shadow:0 2px 8px #0000000d;">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#34C759] flex items-center justify-center">
                    <i class="fa-solid fa-check text-white text-2xl"></i>
                </div>
                <h2 class="font-bold text-2xl uppercase tracking-tight mb-2">Penawaran Terkirim</h2>
                <p class="text-[#444748] text-sm mb-6">Tim kami akan menghubungi Anda dalam 1x24 jam melalui WhatsApp.</p>
                <button wire:click="resetForm" type="button"
                    class="bg-black text-white px-6 py-3 font-bold text-sm uppercase tracking-wider hover:bg-[#333] transition-colors">
                    Kirim Penawaran Lain
                </button>
            </div>
        </section>
    @else
        <section id="form-penjualan" aria-labelledby="form-heading" class="w-full py-12 px-4 md:px-6 bg-[#F8F8F8]">
            <div class="max-w-4xl mx-auto border border-neutral-200 p-6 md:p-8 bg-white" style="box-shadow:0 2px 8px #0000000d;">
                <div class="pb-4 border-b-2 border-black mb-6">
                    <h2 id="form-heading" class="font-bold text-xl md:text-2xl uppercase tracking-tight">
                        Form Penjualan
                    </h2>
                </div>

                <form wire:submit.prevent="submit" class="flex flex-col gap-5">
                    <div>
                        <label for="nama" class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Nama Lengkap</label>
                        <input wire:model="nama" id="nama" type="text" placeholder="Masukkan nama Anda"
                            class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black transition-colors" />
                        @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="whatsapp" class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Nomor WhatsApp</label>
                        <input wire:model="whatsapp" id="whatsapp" type="tel" placeholder="08xxxxxxxxxx"
                            class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black transition-colors" />
                        @error('whatsapp') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div wire:ignore>
                        <label class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Alamat Lengkap</label>
                        <livewire:frontend.address-picker :initialData="[
                            'province_id' => $province_id,
                            'regency_id' => $regency_id,
                            'district_id' => $district_id,
                            'village_id' => $village_id,
                            'address_detail' => $address_detail,
                        ]" 
                        input-class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black transition-colors"
                        label-class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]"
                        />
                        @error('province_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('regency_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        @error('address_detail') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="kategori" class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Kategori Barang</label>
                        <div class="select-wrap">
                            <select wire:model="kategori" id="kategori"
                                class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black transition-colors">
                                <option value="">Pilih Kategori</option>
                                <option value="tv">TV</option>
                                <option value="kulkas">Kulkas</option>
                                <option value="mesin-cuci">Mesin Cuci</option>
                                <option value="ac">AC</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        @error('kategori') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="merek" class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Merek &amp; Tipe</label>
                        <input wire:model="merek" id="merek" type="text" placeholder="Contoh: LG 2 Pintu"
                            class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black transition-colors" />
                        @error('merek') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <fieldset>
                        <legend class="block text-sm font-bold uppercase mb-2 tracking-[0.3px]">Kondisi</legend>
                        <div class="flex gap-6" role="radiogroup" aria-label="Kondisi barang">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input wire:model="kondisi" type="radio" value="baik" class="w-4 h-4 accent-black" />
                                <span class="text-sm text-[#444748]">Baik</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input wire:model="kondisi" type="radio" value="cukup" class="w-4 h-4 accent-black" />
                                <span class="text-sm text-[#444748]">Cukup</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input wire:model="kondisi" type="radio" value="rusak" class="w-4 h-4 accent-black" />
                                <span class="text-sm text-[#444748]">Rusak</span>
                            </label>
                        </div>
                        @error('kondisi') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </fieldset>

                    <div>
                        <label for="deskripsi" class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Deskripsi</label>
                        <textarea wire:model="deskripsi" id="deskripsi" rows="5" placeholder="Jelaskan kondisi barang..."
                            class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black transition-colors resize-none"></textarea>
                        @error('deskripsi') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Upload Foto/Video dengan auto-compress --}}
                    <div class="flex flex-col gap-2" x-data="mediaUploader()">
                        <label for="upload-input" class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Upload Foto/Video (Maks 5 file)</label>
                        <label for="upload-input"
                            class="flex flex-col items-center justify-center gap-2 p-8 border-2 border-dashed border-gray-300 cursor-pointer hover:bg-[#F8F8F8] transition-colors"
                            :class="{ 'pointer-events-none opacity-50': compressing }">
                            <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-3xl" aria-hidden="true"></i>
                            <p class="text-[#7E7576] text-sm">Klik atau drag file ke sini (Foto max 5MB, Video otomatis dikompres)</p>
                            <span class="px-4 py-2 bg-white border border-gray-200 text-black text-sm font-bold mt-2">Pilih File</span>
                            <input x-ref="fileInput" x-on:change="handleFiles($event)" id="upload-input" type="file"
                                accept="image/jpeg,image/png,image/webp,video/mp4,video/quicktime,video/x-msvideo,video/webm"
                                multiple class="hidden" />
                        </label>

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
                    </div>

                    <button type="submit"
                        class="bg-black text-white py-4 uppercase font-bold text-sm tracking-[0.5px] hover:bg-[#333] transition-colors mt-1 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="compressing">
                        Kirim Penawaran
                    </button>
                </form>
            </div>
        </section>
    @endif
</div>

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

    /**
     * Compress a video file using Canvas + MediaRecorder.
     * Target: 480p resolution, ~1 Mbps bitrate, WebM/VP8 output.
     * Audio is preserved via Web Audio API.
     */
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
