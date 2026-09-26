<div class="space-y-6 max-w-5xl mx-auto" x-data="docArticleForm()">

    {{-- Top Action Bar --}}
    <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-xs border border-base-300 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-bold text-base-content truncate">
                    {{ $isEditing ? 'Ubah Artikel Dokumentasi' : 'Tulis Artikel Dokumentasi Baru' }}
                </h2>
                <span class="badge badge-sm shrink-0 {{ $status === 'published' ? 'badge-success text-white' : 'badge-ghost' }}">
                    {{ $status === 'published' ? 'Published' : 'Draft' }}
                </span>
            </div>
            <p class="text-xs text-neutral-500 mt-1 leading-relaxed">
                Gunakan editor visual untuk menyusun panduan tanpa perlu menulis kode HTML/Markdown secara manual.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <x-button label="Kembali" icon="o-arrow-left" class="btn-sm btn-ghost" link="{{ route('admin.docs.index') }}" />
            

            <x-button label="{{ $isEditing ? 'Perbarui Artikel' : 'Simpan Artikel' }}"
                      icon="o-check"
                      class="btn-sm btn-primary"
                      wire:click="save"
                      spinner="save" />
        </div>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 text-sm rounded-xl p-4">
            <p class="font-bold mb-1 flex items-center gap-2">
                <x-icon name="o-exclamation-triangle" class="w-4 h-4" />
                Terdapat kesalahan dalam pengisian form:
            </p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Content --}}
    <div class="space-y-6">

        {{-- Meta Settings Card --}}
        <div class="bg-white p-6 rounded-2xl shadow-xs border border-base-300 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-500 pb-2 border-b border-base-200">
                Informasi & Kategori
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Title --}}
                <div class="md:col-span-2">
                    <x-input id="article-title-field" label="Judul Artikel Dokumentasi" wire:model.live.debounce.400ms="title" placeholder="Contoh: Cara Mengajukan Servis Elektronik Online" required />
                </div>

                {{-- Slug --}}
                <div>
                    <label class="label text-xs font-semibold text-neutral-700">URL Slug</label>
                    <div class="join w-full">
                        <input type="text" wire:model="slug" class="input input-bordered join-item w-full text-xs font-mono" placeholder="cara-mengajukan-servis" required />
                        <button type="button" wire:click="generateSlug" class="btn btn-outline join-item btn-sm text-xs" title="Generate otomatis dari judul">
                            Generate
                        </button>
                    </div>
                    @error('slug') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label class="label text-xs font-semibold text-neutral-700">Kategori Dokumentasi (Role Target)</label>
                    <select wire:model.live="doc_category_id" class="select select-bordered w-full text-xs" required>
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">
                                {{ $cat->name }} ({{ $cat->role_access ? ucfirst($cat->role_access) : 'Publik' }})
                            </option>
                        @endforeach
                    </select>
                    @error('doc_category_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Parent Article (Optional hierarchy) --}}
                <div>
                    <label class="label text-xs font-semibold text-neutral-700">Sub-halaman Dari (Opsional)</label>
                    <select wire:model="parent_id" class="select select-bordered w-full text-xs">
                        <option value="">Sebagai Top-level Artikel (Utama)</option>
                        @foreach($potentialParents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                        @endforeach
                    </select>
                    <span class="text-[11px] text-neutral-400 mt-1 block">Pilih jika artikel ini merupakan bagian dari artikel panduan lain.</span>
                </div>

                {{-- Version & Order & Status --}}
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <x-input label="Versi" wire:model="version" placeholder="1.0" class="text-xs font-mono" required />
                    </div>
                    <div>
                        <x-input label="Urutan" type="number" wire:model="order" min="0" class="text-xs" required />
                    </div>
                    <div>
                        <label class="label text-xs font-semibold text-neutral-700">Status</label>
                        <select wire:model="status" class="select select-bordered w-full text-xs" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                </div>

                {{-- Excerpt / Short Description --}}
                <div class="md:col-span-2">
                    <x-textarea id="article-excerpt-field" label="Deskripsi Singkat / Ringkasan (Lead)" wire:model="excerpt" placeholder="Ringkasan 1-2 kalimat tentang isi panduan ini (tampil di bawah judul dan di hasil pencarian)..." rows="2" />
                </div>
            </div>
        </div>

        {{-- Featured Image Card --}}
        <div class="bg-white p-6 rounded-2xl shadow-xs border border-base-300">
            <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-500 pb-2 border-b border-base-200 mb-4">
                Gambar Utama / Header (Opsional)
            </h3>

            <div class="flex flex-col sm:flex-row gap-6 items-start">
                <div class="w-full sm:w-1/2">
                    <input type="file" wire:model="featured_image_upload" accept="image/png,image/jpeg,image/webp,image/gif" class="file-input file-input-bordered file-input-sm w-full" />
                    <p class="text-xs text-neutral-400 mt-1">Format: JPG, PNG, WEBP, GIF. Maksimal 2MB.</p>
                    <div wire:loading wire:target="featured_image_upload" class="text-xs text-amber-600 mt-1 font-semibold flex items-center gap-1">
                        <span class="loading loading-spinner loading-xs"></span> Mengunggah gambar...
                    </div>
                </div>

                {{-- Preview existing or uploaded --}}
                <div class="w-full sm:w-1/2">
                    @if ($featured_image_upload)
                        <div class="relative rounded-xl overflow-hidden border border-base-300 bg-base-100 max-h-48">
                            <img src="{{ $featured_image_upload->temporaryUrl() }}" class="w-full h-auto object-cover" alt="Preview Gambar" />
                            <button type="button" wire:click="$set('featured_image_upload', null)" class="btn btn-circle btn-xs btn-error absolute top-2 right-2 text-white">✕</button>
                        </div>
                    @elseif ($existing_featured_image)
                        <div class="relative rounded-xl overflow-hidden border border-base-300 bg-base-100 max-h-48">
                            <img src="{{ asset('storage/' . $existing_featured_image) }}" class="w-full h-auto object-cover" alt="Gambar Tersimpan" />
                            <button type="button" wire:click="removeFeaturedImage" class="btn btn-circle btn-xs btn-error absolute top-2 right-2 text-white" title="Hapus gambar">✕</button>
                        </div>
                    @else
                        <div class="border border-dashed border-base-300 rounded-xl p-4 text-center text-xs text-neutral-400 bg-base-50">
                            Belum ada gambar utama dipilih.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- WYSIWYG TinyMCE Content Card --}}
        <div class="bg-white p-6 rounded-2xl shadow-xs border border-base-300" wire:ignore>
            <div class="flex justify-between items-center pb-2 border-b border-base-200 mb-4">
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-500">
                        Konten Dokumentasi (WYSIWYG Editor)
                    </h3>
                    <p class="text-xs text-neutral-400 mt-0.5">
                        Dukungan: Heading (H2/H3), Paragraf, Gambar/Screenshot, Ordered & Unordered List, Code Block, Info/Tip/Warning Callout.
                    </p>
                </div>
            </div>

            <textarea id="tinymce-content">{!! $content !!}</textarea>
        </div>

    </div>


</div>

{{-- Load TinyMCE & Editor Script (Cleanly separated from HTML attributes) --}}
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script>
window.docArticleForm = function() {
    return {
        init() {
            if (window.initDocTinyMCE) {
                window.initDocTinyMCE();
            } else {
                const timer = setInterval(() => {
                    if (window.initDocTinyMCE) {
                        clearInterval(timer);
                        window.initDocTinyMCE();
                    }
                }, 100);
            }
        }
    };
};

window.initDocTinyMCE = function() {
    if (typeof tinymce === 'undefined') {
        setTimeout(window.initDocTinyMCE, 150);
        return;
    }

    // Set baseURL explicitly to prevent TinyMCE from searching relative Laravel paths
    window.tinymce.baseURL = 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3';

    if (tinymce.get('tinymce-content')) {
        tinymce.remove('#tinymce-content');
    }

    tinymce.init({
        selector: '#tinymce-content',
        height: 550,
        menubar: 'file edit view insert format tools table help',
        plugins: 'lists link image code table codesample fullscreen searchreplace wordcount visualblocks',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image codesample | callout_info callout_tip callout_warning | code fullscreen',
        convert_urls: false,
        relative_urls: false,
        remove_script_host: true,
        document_base_url: '/',
        content_style: 'body { font-family: Inter, system-ui, sans-serif; font-size: 15px; line-height: 1.6; color: #334155; padding: 1rem; } ul, ol { padding-left: 1.5rem; } li > ul, li > ol { padding-left: 1.25rem; } img { max-width: 100%; height: auto; max-height: 520px; border-radius: 6px; border: 1px solid #e2e8f0; display: block; margin: 1.25rem auto; } li img, li p img { margin: 0.75rem 0; } pre { background: #0f172a; color: #f8fafc; padding: 1rem; border-radius: 6px; } blockquote.callout-info { border-left: 3px solid #0284c7; background: #f0f9ff; color: #0c4a6e; padding: 0.875rem 1.25rem; border-radius: 0 4px 4px 0; margin: 1rem 0; font-style: normal; } blockquote.callout-tip { border-left: 3px solid #059669; background: #ecfdf5; color: #064e3b; padding: 0.875rem 1.25rem; border-radius: 0 4px 4px 0; margin: 1rem 0; font-style: normal; } blockquote.callout-warning { border-left: 3px solid #d97706; background: #fffbeb; color: #78350f; padding: 0.875rem 1.25rem; border-radius: 0 4px 4px 0; margin: 1rem 0; font-style: normal; }',
        images_upload_url: '{{ route('admin.docs.upload-image') }}',
        automatic_uploads: true,
        images_reuse_filename: false,
        images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route('admin.docs.upload-image') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            xhr.upload.onprogress = (e) => {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = () => {
                if (xhr.status === 403 || xhr.status === 401) {
                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                    return;
                }
                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }
                try {
                    const json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.location !== 'string') {
                        reject('Format response tidak valid.');
                        return;
                    }
                    resolve(json.location);
                } catch (err) {
                    reject('Gagal membaca response server.');
                }
            };

            xhr.onerror = () => {
                reject('Gagal mengunggah gambar. Periksa koneksi internet.');
            };

            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        }),
        setup: (editor) => {
            editor.on('change keyup NodeChange', () => {
                @this.set('content', editor.getContent(), false);
            });

            // Tutup dropdown notifikasi saat klik atau mengetik di dalam TinyMCE editor (iframe)
            editor.on('click focus', () => {
                window.dispatchEvent(new CustomEvent('close-dropdowns'));
            });

            // Callout buttons without emojis
            editor.ui.registry.addButton('callout_info', {
                text: 'Catatan / Info',
                tooltip: 'Sisipkan Kotak Informasi (Biru)',
                onAction: () => {
                    editor.insertContent('<blockquote class="callout-info"><strong>Catatan:</strong> Tuliskan informasi penting di sini.</blockquote><p></p>');
                }
            });

            editor.ui.registry.addButton('callout_tip', {
                text: 'Tips',
                tooltip: 'Sisipkan Kotak Tips (Hijau)',
                onAction: () => {
                    editor.insertContent('<blockquote class="callout-tip"><strong>Tips:</strong> Tuliskan tips yang bermanfaat di sini.</blockquote><p></p>');
                }
            });

            editor.ui.registry.addButton('callout_warning', {
                text: 'Perhatian',
                tooltip: 'Sisipkan Kotak Perhatian (Kuning)',
                onAction: () => {
                    editor.insertContent('<blockquote class="callout-warning"><strong>Perhatian:</strong> Tuliskan peringatan penting di sini.</blockquote><p></p>');
                }
            });
        }
    });
};

document.addEventListener('livewire:navigated', () => {
    if (document.getElementById('tinymce-content')) {
        window.initDocTinyMCE();
    }
});
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('tinymce-content')) {
        window.initDocTinyMCE();
    }
});
</script>
@endpush
