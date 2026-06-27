<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submitted): ?>
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
    <?php else: ?>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label for="whatsapp" class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Nomor WhatsApp</label>
                        <input wire:model="whatsapp" id="whatsapp" type="tel" placeholder="08xxxxxxxxxx"
                            class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black transition-colors" />
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label for="kota" class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Kota</label>
                        <input wire:model="kota" id="kota" type="text" placeholder="Kota domisili barang"
                            class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black transition-colors" />
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['kota'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label for="merek" class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Merek &amp; Tipe</label>
                        <input wire:model="merek" id="merek" type="text" placeholder="Contoh: LG 2 Pintu"
                            class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black transition-colors" />
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['merek'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['kondisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </fieldset>

                    <div>
                        <label for="deskripsi" class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Deskripsi</label>
                        <textarea wire:model="deskripsi" id="deskripsi" rows="5" placeholder="Jelaskan kondisi barang..."
                            class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black transition-colors resize-none"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-bold uppercase mb-1.5 tracking-[0.3px]">Upload Foto (Maks 5MB per file)</label>
                        <label for="upload-foto"
                            class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 p-8 cursor-pointer hover:bg-[#F8F8F8] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="#888" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-width="1.5" d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" stroke-width="1.5" />
                                <line x1="12" y1="3" x2="12" y2="15" stroke-width="1.5" />
                            </svg>
                            <p class="mt-3 text-[#888] text-sm">Klik untuk upload foto</p>
                            <p class="text-xs text-gray-400 mt-1"><?php echo e(count($photos)); ?> file dipilih</p>
                            <input wire:model="photos" type="file" id="upload-foto" accept="image/*" multiple class="hidden" />
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['photos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <button type="submit"
                        class="bg-black text-white py-4 uppercase font-bold text-sm tracking-[0.5px] hover:bg-[#333] transition-colors mt-1">
                        Kirim Penawaran
                    </button>
                </form>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH D:\laragon\www\project_prokar_vibe_coding\resources\views/livewire/frontend/sell-form.blade.php ENDPATH**/ ?>