<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CheckoutModalTest extends DuskTestCase
{
    public function test_checkout_snap_popup_modal_trigger(): void
    {
        $user = User::first() ?: User::factory()->create([
            'email' => 'customer_checkout_dusk@prokar.id',
            'name' => 'Dusk Customer',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/checkout')
                ->waitFor('#checkoutForm', 15)
                ->assertPresent('#checkoutForm')
                ->assertSee('PENGIRIMAN');
        });
    }
}


