<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── Opening Animation default settings ──
        Setting::updateOrCreate(
            ['key' => 'opening_enabled'],
            [
                'value' => '1',
                'type'  => 'boolean',
                'group' => 'general',
                'label' => 'Aktifkan Opening Animation',
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'opening_config'],
            [
                'value' => json_encode([
                    'text_color'    => '#FFFFFF',
                    'bg_color'      => '#000000',
                    'accent_color'  => '#FECB00',
                    'fade_duration' => 900,
                    'show_once'     => true,
                    'messages'      => [
                        ['type' => 'line', 'text' => 'Elektronik rusak bukan akhir dari masa pakainya.', 'duration' => 1900],
                        ['type' => 'line', 'text' => 'Perbaiki. Jual. Beli. Dalam satu platform.', 'duration' => 1900],
                        ['type' => 'logo', 'text' => 'PROKAR ELEKTRONIK', 'duration' => 2400],
                    ]
                ]),
                'type'  => 'json',
                'group' => 'general',
                'label' => 'Konfigurasi Opening Animation',
            ]
        );

        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            CategoryProductSeeder::class,
        ]);
    }
}
