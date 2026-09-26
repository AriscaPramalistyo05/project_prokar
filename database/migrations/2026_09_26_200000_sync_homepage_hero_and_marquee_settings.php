<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations to synchronize hero and marquee settings to production.
     */
    public function up(): void
    {
        $settings = [
            'hero_badge'            => ['value' => 'PROKAR ELEKTRONIK · MLONGGO JEPARA', 'label' => 'Hero Badge'],
            'hero_headline_1'       => ['value' => 'Elektronik Bekas', 'label' => 'Hero Headline 1'],
            'hero_headline_color_1' => ['value' => 'hitam', 'label' => 'Hero Headline Color 1'],
            'hero_headline_2'       => ['value' => '', 'label' => 'Hero Headline 2'],
            'hero_headline_color_2' => ['value' => 'hitam', 'label' => 'Hero Headline Color 2'],
            'hero_headline_3'       => ['value' => 'Siap Dipakai', 'label' => 'Hero Headline 3'],
            'hero_headline_color_3' => ['value' => 'kuning', 'label' => 'Hero Headline Color 3'],
            'hero_subheadline'      => ['value' => 'Jual, beli, dan servis elektronik rumah tangga dengan pilihan yang sudah diperiksa teknisi.', 'label' => 'Hero Subheadline'],
            'marquee_text_black'    => ['value' => 'nikmati produk second berkualitas dengan harga murah', 'label' => 'Marquee Hitam'],
        ];

        foreach ($settings as $key => $data) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value'       => $data['value'],
                    'label'       => $data['label'],
                    'group'       => 'homepage',
                    'type'        => 'text',
                    'updated_at'  => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
