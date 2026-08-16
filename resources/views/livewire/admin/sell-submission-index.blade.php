<div>
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Barang Masuk (Jual)</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar pengajuan jual dari pelanggan</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <div class="w-full md:w-80">
            <x-input placeholder="Cari kode atau nama..." wire:model.live.debounce="search" icon="o-magnifying-glass" clearable class="bg-white border-gray-200 focus:border-gray-300 focus:ring-0 shadow-sm" />
        </div>
        <div class="w-full md:w-56">
            <x-select 
                wire:model.live="statusFilter" 
                :options="[
                    ['id' => '', 'name' => 'Semua Status'],
                    ['id' => 'pending', 'name' => 'Menunggu'],
                    ['id' => 'reviewing', 'name' => 'Sedang Direview'],
                    ['id' => 'negotiating', 'name' => 'Negosiasi'],
                    ['id' => 'accepted', 'name' => 'Diterima'],
                    ['id' => 'rejected', 'name' => 'Ditolak'],
                    ['id' => 'paid', 'name' => 'Sudah Dibayar'],
                    ['id' => 'in_repair', 'name' => 'Sedang Diperbaiki'],
                    ['id' => 'ready_for_sale', 'name' => 'Siap Dijual'],
                ]" 
                option-value="id" 
                option-label="name"
                class="bg-white border-gray-200 focus:border-gray-300 focus:ring-0 shadow-sm"
            />
        </div>
    </div>

    @php
        $headers = [
            ['key' => 'submission_code', 'label' => 'Kode'],
            ['key' => 'customer_name', 'label' => 'Pelanggan'],
            ['key' => 'category.name', 'label' => 'Kategori'],
            ['key' => 'device_brand', 'label' => 'Barang'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'action', 'label' => 'Aksi', 'sortable' => false]
        ];
    @endphp

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <x-table :headers="$headers" :rows="$submissions" with-pagination
            class="bg-white [&_th]:text-xs [&_th]:uppercase [&_th]:tracking-wide [&_th]:text-gray-500 [&_th]:font-semibold [&_th]:bg-white [&_th]:border-b [&_th]:border-gray-100 [&_th]:py-4 [&_tbody_tr:hover]:bg-[#f9fafb] [&_tbody_tr]:transition-colors [&_tbody_tr]:border-b [&_tbody_tr]:border-gray-50 [&_td]:py-3 [&_td]:text-sm"
        >
            @scope('cell_submission_code', $submission)
                <span class="font-mono font-bold text-gray-900 tracking-tight">{{ $submission->submission_code }}</span>
            @endscope

            @scope('cell_customer_name', $submission)
                <span class="font-medium text-gray-900">{{ $submission->customer_name }}</span>
            @endscope

            @scope('cell_device_brand', $submission)
                <span class="text-gray-800">{{ $submission->device_brand }} {{ $submission->device_model }}</span>
            @endscope

            @scope('cell_status', $submission)
                @php
                    $color = match($submission->status) {
                        'pending' => 'bg-gray-100 text-gray-700',
                        'reviewing' => 'bg-blue-100 text-blue-700',
                        'negotiating' => 'bg-purple-100 text-purple-700',
                        'accepted' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                        'paid' => 'bg-green-700 text-white',
                        'in_repair' => 'bg-black text-white',
                        'ready_for_sale' => 'bg-[#FECB00] text-black',
                        default => 'bg-gray-100 text-gray-700',
                    };
                    $label = match($submission->status) {
                        'pending' => 'Menunggu',
                        'reviewing' => 'Sedang Direview',
                        'negotiating' => 'Negosiasi',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                        'paid' => 'Sudah Dibayar',
                        'in_repair' => 'Sedang Diperbaiki',
                        'ready_for_sale' => 'Siap Dijual',
                        default => $submission->status,
                    };
                @endphp
                <span class="px-2 py-0.5 text-[10px] md:text-xs uppercase font-bold tracking-wide rounded-sm whitespace-nowrap {{ $color }}">
                    {{ $label }}
                </span>
            @endscope

            @scope('cell_action', $submission)
                <div class="flex">
                    <x-button 
                        icon="o-eye" 
                        link="{{ route('admin.sell-submissions.show', $submission) }}" 
                        class="btn-sm btn-ghost text-gray-600 hover:text-blue-600" 
                        tooltip="Detail" 
                    />
                </div>
            @endscope

            <x-slot:empty>
                <div class="flex flex-col items-center justify-center py-16">
                    <x-icon name="o-inbox-arrow-down" class="w-16 h-16 text-gray-300 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada data barang masuk</h3>
                    <p class="text-sm text-gray-500 mt-1 italic">Belum ada pengajuan atau tidak ada yang sesuai dengan filter.</p>
                </div>
            </x-slot:empty>
        </x-table>
    </div>
</div>
