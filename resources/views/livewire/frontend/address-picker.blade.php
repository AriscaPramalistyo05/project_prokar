<div x-data="addressPickerData(
        '{{ $province_id ?? '' }}',
        '{{ $regency_id ?? '' }}',
        '{{ $district_id ?? '' }}',
        '{{ $village_id ?? '' }}',
        '{{ $postal_code ?? '' }}',
        '{{ addslashes($address_detail ?? '') }}'
    )"
     x-init="init()"
     class="space-y-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Provinsi -->
        <div>
            <label class="{{ $labelClass }}">Provinsi <span class="text-red-500">*</span></label>
            <div class="relative">
                <select x-model="province" @change="onProvinceChange()" class="{{ $inputClass }} appearance-none bg-transparent cursor-pointer">
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
                <select x-model="regency" @change="onRegencyChange()" :disabled="!province" class="{{ $inputClass }} appearance-none bg-transparent disabled:opacity-50 cursor-pointer">
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
                <select x-model="district" @change="onDistrictChange()" :disabled="!regency" class="{{ $inputClass }} appearance-none bg-transparent disabled:opacity-50 cursor-pointer">
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
                <select x-model="village" @change="syncToParent()" :disabled="!district" class="{{ $inputClass }} appearance-none bg-transparent disabled:opacity-50 cursor-pointer">
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

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
        <div class="sm:col-span-1">
            <label class="{{ $labelClass }}">Kode Pos <span class="text-red-500">*</span></label>
            <input type="text" x-model="postal_code" @input.debounce.300ms="syncToParent()" class="{{ $inputClass }}" placeholder="Masukkan Kode Pos">
        </div>
        <div class="sm:col-span-2">
            <label class="{{ $labelClass }}">Detail Alamat (Jalan, RT/RW, Patokan) <span class="text-red-500">*</span></label>
            <textarea x-model="address_detail" @input.debounce.300ms="syncToParent()" rows="3" class="{{ $inputClass }}" placeholder="Contoh: Jl. Diponegoro No.10, RT 01/RW 02, Samping Masjid"></textarea>
        </div>
    </div>
    
    <script>
        (function() {
            window.emsifaCache = window.emsifaCache || {};

            if (typeof window.addressPickerDataFn === 'undefined') {
                window.addressPickerDataFn = true;

                window.addressPickerData = function(initProvince, initRegency, initDistrict, initVillage, initPostal, initDetail) {
                    return {
                        province: initProvince || '',
                        regency: initRegency || '',
                        district: initDistrict || '',
                        village: initVillage || '',
                        postal_code: initPostal || '',
                        address_detail: initDetail || '',

                        provinces: [],
                        regencies: [],
                        districts: [],
                        villages: [],

                        async init() {
                            await this.fetchProvinces();
                            if (this.province) await this.fetchRegenciesLoad();
                            if (this.regency) await this.fetchDistrictsLoad();
                            if (this.district) await this.fetchVillagesLoad();
                            if (this.province || this.regency || this.address_detail) {
                                this.syncToParent();
                            }
                        },

                        syncToParent() {
                            const selProvince = Array.isArray(this.provinces) ? this.provinces.find(p => p.id == this.province) : null;
                            const selRegency = Array.isArray(this.regencies) ? this.regencies.find(r => r.id == this.regency) : null;
                            const selDistrict = Array.isArray(this.districts) ? this.districts.find(d => d.id == this.district) : null;

                            const payload = {
                                city: selRegency ? selRegency.name : (this.regency || ''),
                                regency_name: selRegency ? selRegency.name : '',
                                regency_id: this.regency || '',
                                province_id: this.province || '',
                                province_name: selProvince ? selProvince.name : '',
                                district_id: this.district || '',
                                district_name: selDistrict ? selDistrict.name : '',
                                village_id: this.village || '',
                                postal_code: this.postal_code || '',
                                address_detail: this.address_detail || '',
                            };
                            // Dispatch globally so parent ServiceForm/SellForm/CheckoutSummary can listen via #[On('address-updated')]
                            if (typeof Livewire !== 'undefined') {
                                Livewire.dispatch('address-updated', payload);
                            }
                        },

                        async onProvinceChange() {
                            this.regency = ''; this.district = ''; this.village = '';
                            this.regencies = []; this.districts = []; this.villages = [];
                            this.syncToParent();
                            if (this.province) await this.fetchRegencies();
                        },

                        async onRegencyChange() {
                            this.district = ''; this.village = '';
                            this.districts = []; this.villages = [];
                            this.syncToParent();
                            if (this.regency) await this.fetchDistricts();
                        },

                        async onDistrictChange() {
                            this.village = ''; this.villages = [];
                            this.syncToParent();
                            if (this.district) await this.fetchVillages();
                        },

                        async fetchProvinces() {
                            const key = 'provinces';
                            if (window.emsifaCache[key]) { this.provinces = window.emsifaCache[key]; return; }
                            try {
                                const res = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
                                if (res.ok) { const d = await res.json(); window.emsifaCache[key] = d; this.provinces = d; }
                            } catch(e) { console.error('provinces err', e); }
                        },

                        async fetchRegencies() {
                            const key = `regencies_${this.province}`;
                            if (window.emsifaCache[key]) { this.regencies = window.emsifaCache[key]; return; }
                            try {
                                const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.province}.json`);
                                if (res.ok) { const d = await res.json(); window.emsifaCache[key] = d; this.regencies = d; }
                            } catch(e) { console.error('regencies err', e); }
                        },

                        async fetchRegenciesLoad() {
                            await this.fetchRegencies();
                        },

                        async fetchDistricts() {
                            const key = `districts_${this.regency}`;
                            if (window.emsifaCache[key]) { this.districts = window.emsifaCache[key]; return; }
                            try {
                                const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.regency}.json`);
                                if (res.ok) { const d = await res.json(); window.emsifaCache[key] = d; this.districts = d; }
                            } catch(e) { console.error('districts err', e); }
                        },

                        async fetchDistrictsLoad() {
                            await this.fetchDistricts();
                        },

                        async fetchVillages() {
                            const key = `villages_${this.district}`;
                            if (window.emsifaCache[key]) { this.villages = window.emsifaCache[key]; return; }
                            try {
                                const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${this.district}.json`);
                                if (res.ok) { const d = await res.json(); window.emsifaCache[key] = d; this.villages = d; }
                            } catch(e) { console.error('villages err', e); }
                        },

                        async fetchVillagesLoad() {
                            await this.fetchVillages();
                        },
                    };
                };
            }
        })();
    </script>
</div>

