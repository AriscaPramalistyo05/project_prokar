{{-- Address Picker Partial - digunakan di dalam form Livewire (Servis & Jual) --}}
{{-- $wire di sini merujuk ke parent Livewire component (ServiceForm/SellForm) --}}
<div
    wire:ignore
    x-data="{
        province: '33',
        regency: '{{ $regency_id ?? '' }}',
        district: '{{ $district_id ?? '' }}',
        village: '{{ $village_id ?? '' }}',
        address_detail: '{{ addslashes($address_detail ?? '') }}',
        regencies: [],
        districts: [],
        villages: [],
        async init() {
            window.emsifaCache = window.emsifaCache || {};
            this.push('province', '33');
            await this.loadRegencies();
            if (this.regency) await this.loadDistricts();
            if (this.district) await this.loadVillages();

            this.$watch('$wire.province_id', (val) => {
                if (!val || val !== '33') {
                    this.province = '33';
                    this.push('province', '33');
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
        async loadRegencies() {
            const k = 'reg_33_target';
            if (window.emsifaCache[k]) { 
                this.regencies = window.emsifaCache[k]; 
                return; 
            }
            try { 
                const r = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/regencies/33.json'); 
                if(r.ok){
                    const d = await r.json(); 
                    // 8 Wilayah Cakupan Prokar di Jawa Tengah
                    const targetIds = ['3320', '3319', '3321', '3318', '3374', '3322', '3315', '3317'];
                    const filtered = d.filter(item => targetIds.includes(item.id));
                    // Urutkan dengan Jepara paling atas
                    filtered.sort((a, b) => (a.id === '3320' ? -1 : (b.id === '3320' ? 1 : a.name.localeCompare(b.name))));
                    window.emsifaCache[k] = filtered; 
                    this.regencies = filtered;
                } 
            } catch(e){
                // Fallback static jika offline
                this.regencies = [
                    { id: '3320', name: 'KABUPATEN JEPARA' },
                    { id: '3319', name: 'KABUPATEN KUDUS' },
                    { id: '3321', name: 'KABUPATEN DEMAK' },
                    { id: '3318', name: 'KABUPATEN PATI' },
                    { id: '3374', name: 'KOTA SEMARANG' },
                    { id: '3322', name: 'KABUPATEN SEMARANG' },
                    { id: '3315', name: 'KABUPATEN GROBOGAN' },
                    { id: '3317', name: 'KABUPATEN REMBANG' }
                ];
            }
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

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="{{ $labelClass ?? '' }}">Kabupaten / Kota <span class="text-red-500">*</span></label>
            <div class="relative">
                <select x-model="regency" @change="onRegency()" class="{{ $inputClass ?? '' }} appearance-none bg-transparent cursor-pointer">
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
            <label class="{{ $labelClass ?? '' }}">Desa / Kelurahan <span class="text-red-500">*</span></label>
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
        <label class="{{ $labelClass ?? '' }}">Detail Alamat (Jalan, RT/RW, Patokan Rumah) <span class="text-red-500">*</span></label>
        <textarea x-model="address_detail" @input.debounce.500ms="pushDetail()" rows="3" class="{{ $inputClass ?? '' }}" placeholder="Contoh: Jl. Karanggondang RT 04/RW 02, Samping Masjid Al-Hikmah"></textarea>
    </div>
</div>
