<div x-data="addressPicker()" x-init="initData()" class="space-y-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Provinsi -->
        <div>
            <label class="{{ $labelClass }}">Provinsi <span class="text-red-500">*</span></label>
            <div class="relative">
                <select x-model="province" @change="fetchRegencies()" class="{{ $inputClass }} appearance-none bg-transparent" required>
                    <option value="">-- Pilih Provinsi --</option>
                    <template x-for="p in provinces" :key="p.id">
                        <option :value="p.id" x-text="p.name" :selected="p.id == province"></option>
                    </template>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Kabupaten/Kota -->
        <div>
            <label class="{{ $labelClass }}">Kabupaten/Kota <span class="text-red-500">*</span></label>
            <div class="relative">
                <select x-model="regency" @change="fetchDistricts()" :disabled="!province" class="{{ $inputClass }} appearance-none bg-transparent disabled:opacity-50" required>
                    <option value="">-- Pilih Kab/Kota --</option>
                    <template x-for="r in regencies" :key="r.id">
                        <option :value="r.id" x-text="r.name" :selected="r.id == regency"></option>
                    </template>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Kecamatan -->
        <div>
            <label class="{{ $labelClass }}">Kecamatan <span class="text-red-500">*</span></label>
            <div class="relative">
                <select x-model="district" @change="fetchVillages()" :disabled="!regency" class="{{ $inputClass }} appearance-none bg-transparent disabled:opacity-50" required>
                    <option value="">-- Pilih Kecamatan --</option>
                    <template x-for="d in districts" :key="d.id">
                        <option :value="d.id" x-text="d.name" :selected="d.id == district"></option>
                    </template>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Desa/Kelurahan -->
        <div>
            <label class="{{ $labelClass }}">Desa/Kelurahan <span class="text-red-500">*</span></label>
            <div class="relative">
                <select x-model="village" @change="updateNames()" :disabled="!district" class="{{ $inputClass }} appearance-none bg-transparent disabled:opacity-50" required>
                    <option value="">-- Pilih Desa/Kelurahan --</option>
                    <template x-for="v in villages" :key="v.id">
                        <option :value="v.id" x-text="v.name" :selected="v.id == village"></option>
                    </template>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </div>
            </div>
        </div>
    </div>

    <div>
        <label class="{{ $labelClass }}">Detail Alamat (Jalan, RT/RW, Patokan) <span class="text-red-500">*</span></label>
        <textarea x-model="address_detail" @input="updateNames()" rows="3" class="{{ $inputClass }}" placeholder="Contoh: Jl. Diponegoro No.10, RT 01/RW 02, Samping Masjid" required></textarea>
    </div>
    
    <!-- Hidden fields to sync with Livewire -->
    <input type="hidden" wire:model.live="province_id" x-model="province">
    <input type="hidden" wire:model.live="regency_id" x-model="regency">
    <input type="hidden" wire:model.live="district_id" x-model="district">
    <input type="hidden" wire:model.live="village_id" x-model="village">
    <input type="hidden" wire:model.live="province_name" x-model="province_name">
    <input type="hidden" wire:model.live="regency_name" x-model="regency_name">
    <input type="hidden" wire:model.live="district_name" x-model="district_name">
    <input type="hidden" wire:model.live="village_name" x-model="village_name">
    <input type="hidden" wire:model.live="address_detail" x-model="address_detail">

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('addressPicker', () => ({
                province: @entangle('province_id'),
                regency: @entangle('regency_id'),
                district: @entangle('district_id'),
                village: @entangle('village_id'),
                address_detail: @entangle('address_detail'),
                province_name: @entangle('province_name'),
                regency_name: @entangle('regency_name'),
                district_name: @entangle('district_name'),
                village_name: @entangle('village_name'),
                
                provinces: [],
                regencies: [],
                districts: [],
                villages: [],
                
                async initData() {
                    await this.fetchProvinces();
                    if (this.province) await this.fetchRegencies();
                    if (this.regency) await this.fetchDistricts();
                    if (this.district) await this.fetchVillages();
                },
                
                async fetchProvinces() {
                    const res = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
                    this.provinces = await res.json();
                },
                
                async fetchRegencies() {
                    this.regency = '';
                    this.district = '';
                    this.village = '';
                    this.regencies = [];
                    this.districts = [];
                    this.villages = [];
                    this.updateNames();
                    if (!this.province) return;
                    
                    const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.province}.json`);
                    this.regencies = await res.json();
                },
                
                async fetchDistricts() {
                    this.district = '';
                    this.village = '';
                    this.districts = [];
                    this.villages = [];
                    this.updateNames();
                    if (!this.regency) return;
                    
                    const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.regency}.json`);
                    this.districts = await res.json();
                },
                
                async fetchVillages() {
                    this.village = '';
                    this.villages = [];
                    this.updateNames();
                    if (!this.district) return;
                    
                    const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${this.district}.json`);
                    this.villages = await res.json();
                },
                
                updateNames() {
                    const p = this.provinces.find(x => x.id == this.province);
                    const r = this.regencies.find(x => x.id == this.regency);
                    const d = this.districts.find(x => x.id == this.district);
                    const v = this.villages.find(x => x.id == this.village);
                    
                    this.province_name = p ? p.name : '';
                    this.regency_name = r ? r.name : '';
                    this.district_name = d ? d.name : '';
                    this.village_name = v ? v.name : '';
                }
            }));
        });
    </script>
</div>
