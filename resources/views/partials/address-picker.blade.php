{{-- Address Picker Partial - digunakan di dalam form Livewire --}}
{{-- $wire di sini merujuk ke parent Livewire component (ServiceForm/SellForm) --}}
<div
    wire:ignore
    x-data="{
        province: '{{ $province_id ?? '' }}',
        regency: '{{ $regency_id ?? '' }}',
        district: '{{ $district_id ?? '' }}',
        village: '{{ $village_id ?? '' }}',
        address_detail: '{{ addslashes($address_detail ?? '') }}',
        provinces: [],
        regencies: [],
        districts: [],
        villages: [],
        async init() {
            window.emsifaCache = window.emsifaCache || {};
            await this.loadProvinces();
            if (this.province) await this.loadRegencies();
            if (this.regency) await this.loadDistricts();
            if (this.district) await this.loadVillages();

            this.$watch('$wire.province_id', (val) => {
                if (!val) {
                    this.province = ''; this.regency = ''; this.district = ''; this.village = ''; this.address_detail = '';
                    this.regencies = []; this.districts = []; this.villages = [];
                }
            });
        },
        push(field, val) {
            this[field] = val;
            $wire.set(field === 'province' ? 'province_id' :
                      field === 'regency'  ? 'regency_id' :
                      field === 'district' ? 'district_id' :
                      field === 'village'  ? 'village_id' : field, val);
        },
        pushDetail() {
            $wire.set('address_detail', this.address_detail);
        },
        async onProvince() {
            this.push('province', this.province);
            this.regency = ''; this.district = ''; this.village = '';
            this.regencies = []; this.districts = []; this.villages = [];
            $wire.set('regency_id',''); $wire.set('district_id',''); $wire.set('village_id','');
            if (this.province) await this.loadRegencies();
        },
        async onRegency() {
            this.push('regency', this.regency);
            this.district = ''; this.village = '';
            this.districts = []; this.villages = [];
            $wire.set('district_id',''); $wire.set('village_id','');
            if (this.regency) await this.loadDistricts();
        },
        async onDistrict() {
            this.push('district', this.district);
            this.village = ''; this.villages = [];
            $wire.set('village_id','');
            if (this.district) await this.loadVillages();
        },
        onVillage() {
            this.push('village', this.village);
        },
        async loadProvinces() {
            const k = 'provinces';
            if (window.emsifaCache[k]) { this.provinces = window.emsifaCache[k]; return; }
            try { const r = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json'); if(r.ok){const d=await r.json(); window.emsifaCache[k]=d; this.provinces=d;} } catch(e){}
        },
        async loadRegencies() {
            const k = 'reg_'+this.province;
            if (window.emsifaCache[k]) { this.regencies = window.emsifaCache[k]; return; }
            try { const r = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/regencies/'+this.province+'.json'); if(r.ok){const d=await r.json(); window.emsifaCache[k]=d; this.regencies=d;} } catch(e){}
        },
        async loadDistricts() {
            const k = 'dis_'+this.regency;
            if (window.emsifaCache[k]) { this.districts = window.emsifaCache[k]; return; }
            try { const r = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/districts/'+this.regency+'.json'); if(r.ok){const d=await r.json(); window.emsifaCache[k]=d; this.districts=d;} } catch(e){}
        },
        async loadVillages() {
            const k = 'vil_'+this.district;
            if (window.emsifaCache[k]) { this.villages = window.emsifaCache[k]; return; }
            try { const r = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/villages/'+this.district+'.json'); if(r.ok){const d=await r.json(); window.emsifaCache[k]=d; this.villages=d;} } catch(e){}
        }
    }"
    class="space-y-4">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="{{ $labelClass ?? '' }}">Provinsi <span class="text-red-500">*</span></label>
            <div class="relative">
                <select x-model="province" @change="onProvince()" class="{{ $inputClass ?? '' }} appearance-none bg-transparent cursor-pointer">
                    <option value="">-- Pilih Provinsi --</option>
                    <template x-for="p in provinces" :key="p.id">
                        <option :value="p.id" x-text="p.name"></option>
                    </template>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </div>
            </div>
        </div>

        <div>
            <label class="{{ $labelClass ?? '' }}">Kabupaten/Kota <span class="text-red-500">*</span></label>
            <div class="relative">
                <select x-model="regency" @change="onRegency()" :disabled="!province" class="{{ $inputClass ?? '' }} appearance-none bg-transparent disabled:opacity-50 cursor-pointer">
                    <option value="">-- Pilih Kab/Kota --</option>
                    <template x-for="r in regencies" :key="r.id">
                        <option :value="r.id" x-text="r.name"></option>
                    </template>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </div>
            </div>
        </div>

        <div>
            <label class="{{ $labelClass ?? '' }}">Kecamatan <span class="text-red-500">*</span></label>
            <div class="relative">
                <select x-model="district" @change="onDistrict()" :disabled="!regency" class="{{ $inputClass ?? '' }} appearance-none bg-transparent disabled:opacity-50 cursor-pointer">
                    <option value="">-- Pilih Kecamatan --</option>
                    <template x-for="d in districts" :key="d.id">
                        <option :value="d.id" x-text="d.name"></option>
                    </template>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </div>
            </div>
        </div>

        <div>
            <label class="{{ $labelClass ?? '' }}">Desa/Kelurahan <span class="text-red-500">*</span></label>
            <div class="relative">
                <select x-model="village" @change="onVillage()" :disabled="!district" class="{{ $inputClass ?? '' }} appearance-none bg-transparent disabled:opacity-50 cursor-pointer">
                    <option value="">-- Pilih Desa/Kelurahan --</option>
                    <template x-for="v in villages" :key="v.id">
                        <option :value="v.id" x-text="v.name"></option>
                    </template>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </div>
            </div>
        </div>
    </div>

    <div>
        <label class="{{ $labelClass ?? '' }}">Detail Alamat (Jalan, RT/RW, Patokan) <span class="text-red-500">*</span></label>
        <textarea x-model="address_detail" @input.debounce.500ms="pushDetail()" rows="3" class="{{ $inputClass ?? '' }}" placeholder="Contoh: Jl. Diponegoro No.10, RT 01/RW 02, Samping Masjid"></textarea>
    </div>
</div>
