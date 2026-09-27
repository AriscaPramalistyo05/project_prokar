<?php

namespace Tests\Feature;

use Livewire\Livewire;
use Tests\TestCase;

class AddressPickerTest extends TestCase
{
    public function test_address_picker_renders_with_provinces(): void
    {
        Livewire::test('frontend.address-picker')
            ->assertSee('JAWA TENGAH')
            ->assertSee('DKI JAKARTA')
            ->assertSee('JAWA TIMUR')
            ->assertSee('BALI')
            ->assertSee('33')
            ->assertSee('31');
    }

    public function test_address_picker_mounts_with_initial_data(): void
    {
        Livewire::test('frontend.address-picker', [
            'initialData' => [
                'province_id' => '33',
                'regency_id' => '3320',
                'district_id' => '332001',
                'village_id' => '332001001',
                'address_detail' => 'Jl. Pemuda No. 1',
            ]
        ])
            ->assertSet('province_id', '33')
            ->assertSet('regency_id', '3320')
            ->assertSee('JAWA TENGAH');
    }
}
